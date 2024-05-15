@extends('layouts.page')

@section('body')
    <h1>Supabase basic CRUD with AlpineJS</h1>
    <section x-data="appData" class="mb-1">
        <div class="mb-1">
            <div class="flex">
                <button id="btnAdd" data-target="save" class="pure-button" @click="addUser">Create new user</button>
                <div class="pure-form">
                    <input type="text" class="pure-input-rounded" x-model="s" placeholder="Search by name/email"
                        @keyup="search" />
                    <button class="pure-button reset" @click="resetSearch">&nbsp;</button>
                </div>
            </div>
        </div>

        <section class="data">
            <table class="pure-table pure-table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="user in users">
                        <tr>
                            <td x-text="user.name" class="fading"></td>
                            <td x-text="user.email" class="fading"></td>
                            <td class="fading">
                                <button :data-id="user.id" data-action="edit" data-target="edit"
                                    class="pure-button pure-button-primary" @click="process">Edit</button>
                                <button :data-id="user.id" data-action="delete" class="pure-button pure-button-active"
                                    @click="process">Drop</button>
                            </td>
                        </tr>
                    </template>
                </tbody>

                <template x-if="search_mode">
                    <tfoot>
                        <tr class="tfoot">
                            <td colspan="3">
                                <section class="flex">
                                    <div>We found <b x-text="users.length"></b> matches for: <b x-text="s"></b></div>
                                </section>
                            </td>
                        </tr>
                    </tfoot>
                </template>

                <template x-if="!search_mode">
                    <tfoot>
                        <tr class="tfoot">
                            <td colspan="3">
                                <section class="flex">
                                    <div>Total records: <b x-text="total_users"></b>, page <b x-text="current_page"></b> of
                                        <b x-text="total_pages"></b>
                                    </div>
                                    <div>
                                        Page&nbsp;
                                        <select @change="setCurrentPage">
                                            <template x-for="i in pages">
                                                <option :value="i" x-text="i" :selected="i == current_page">
                                                </option>
                                            </template>
                                        </select>
                                    </div>
                                </section>
                            </td>
                        </tr>
                    </tfoot>
                </template>
            </table>

            <p>&nbsp;</p>
        </section>

        <section class="modal form-edit">
            <form id="form-edit" class="pure-form pure-g" @submit.prevent="update">
                @include('includes.form')

                <button class="fab close" data-target="close" @click="shutDownModal"></button>

                <button class="fab edit" @click="update"></button>
            </form>
        </section>

        <section class="modal form-save">
            <form id="form-save" class="pure-form pure-g" @submit.prevent="save">
                @include('includes.form')

                <button class="fab close" data-target="save" @click="shutDownModal" type="button"></button>

                <button class="fab save" type="submit"></button>
            </form>
        </section>
    </section>

    <!-- you have to note this element is outside the Alpine scope -->
    <button class="fab add" onclick="common.showModalAdd()"></button>
@endsection

@push('scripts')
    <script>
        // set data + UX utils
        var common = @json($data);

        common.loader = document.querySelector('oa-loader');
        common.dialog = document.querySelector('oa-dialogs');
        common.toast = document.querySelector('oa-toast');
        common.showModalAdd = () => {
            // this is a simple trick to call an Alpine method outside its scope
            document.getElementById('btnAdd').click();
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('appData', () => ({
                search: [],
                search_mode: false,
                s: '',
                users: common.users.slice(0, common.users_per_page),
                total_users: common.users.length,
                users_per_page: common.users_per_page,
                pages: common.pages,
                total_pages: common.pages.length,
                current_page: common.current_page,
                app_dir: common.app_dir,
                // modal
                modalEnabled: false,
                user_id: null,
                user_name: '',
                user_email: '',
                label: '',
                setCurrentPage(e) {
                    this.current_page = Number(e.target.value)

                    this.paginate()
                },
                search() {
                    let x = this.s.trim()

                    if (x.length > 2) {
                        this.search_mode = true

                        this.users = this.performSearch(x.toLowerCase())
                    }

                    if (x.length == 0) {
                        this.resetSearch()
                    }
                },
                resetSearch() {
                    this.search_mode = false

                    this.s = ''

                    this.paginate()
                },
                process(e) {
                    const x = e.target.dataset,
                        obj = this.users.find(i => i.id == x.id);

                    switch (x.action) {
                        case 'edit':
                            this.edit(obj, x);
                            break;
                        case 'delete':
                            this.drop(obj); // delete is a reserved JS' word
                            break;
                    }
                },
                addUser(e) {
                    const x = e.target.dataset.target;

                    this.label = 'create'

                    this.enableModal(x)
                },
                save() {
                    // you must add some validation rules here
                    //...

                    common.dialog.set(`Comfirm you want to add this new user:\n${this.user_name}.`,
                        () => {
                            common.loader.show();

                            axios.post(`${this.app_dir}/users`, {
                                    name: this.user_name,
                                    email: this.user_email,
                                })
                                .then((res) => {
                                    if (res.data.result == 'OK') {
                                        common.users = res.data.users;

                                        this.total_users = common.users.length

                                        common.total_users = this.total_users;

                                        // reset search
                                        if (this.search_mode) {
                                            this.resetSearch(false);
                                        } else {
                                            this.paginate()
                                        }

                                        this.setPager()

                                        this.shutDownModal()

                                        common.toast.success(res.data.message)
                                    } else {
                                        common.toast.error(res.data.message)
                                    }

                                    common.loader.hide();
                                })
                                .catch((err) => {
                                    common.toast.error('Server connection error!')
                                })
                        })
                },
                edit(obj, dataset) {
                    const x = dataset.target;

                    // set user
                    this.user_id = obj.id
                    this.user_name = obj.name
                    this.user_email = obj.email

                    this.label = 'edit'

                    this.enableModal(x);
                },
                update() {
                    // you must add some validations here
                    // ...

                    common.dialog.set(`Confirm you want to update this user data:\n${this.user_name}.`,
                        () => {
                            common.loader.show()

                            axios.put(`${this.app_dir}/users?id=${this.user_id}`, {
                                    id: this.user_id,
                                    name: this.user_name,
                                    email: this.user_email,
                                })
                                .then((res) => {
                                    if (res.data.result == 'OK') {
                                        common.users = res.data.users;

                                        if (this.search_mode) {
                                            this.resetSearch();
                                        } else {
                                            this.paginate()
                                        }

                                        this.shutDownModal()

                                        common.toast.success(res.data.message)
                                    } else {
                                        common.toast.error(res.data.message)
                                    }

                                    common.loader.hide();
                                })
                                .catch((err) => {
                                    common.toast.error('Server connection error!')
                                })
                        })
                },
                drop(obj) {
                    common.dialog.set(
                        `Are you sure you want to delete this user\n${obj.name}?`,
                        () => {
                            // set action on label
                            this.label = 'delete'

                            common.loader.show();

                            axios.delete(`${this.app_dir}/users?id=${obj.id}`)
                                .then((res) => {
                                    if (res.data.result == 'OK') {
                                        this.updateData(res.data, obj)
                                    } else {
                                        common.toast.error(res.data.message)
                                    }

                                    common.loader.hide();
                                })
                                .catch((err) => {
                                    common.toast.error('Server connection error!')
                                })
                        }
                    )
                },
                paginate() {
                    const x = (this.current_page - 1) * common.users_per_page

                    this.users = common.users.slice(x, x + common.users_per_page)
                },
                setPager() {
                    // total_pages
                    const x = Math.ceil(this.total_users / this.users_per_page);

                    // updating data
                    this.total_pages = x;

                    // check if pages length has changed
                    if (x != this.pages.length) {
                        // pages
                        let y = [...this.pages];

                        switch (this.label) {

                            case 'delete':
                                // there's 1 less page, we need to remove the last one
                                y.pop();

                                common.pages = [...y];
                                this.pages = common.pages;

                                // if current_page isn't in pages go to the last page
                                if (!this.pages.includes(this.current_page)) {
                                    common.current_page = this.pages.slice(-1)[0]

                                    this.current_page = common.current_page

                                    // repaginate to update page cursor
                                    this.paginate()
                                }

                                break;

                            case 'create':
                                // the pages are increased by 1
                                y.push(x);

                                common.pages = [...y]

                                break;
                        }

                        let z = [];
                        for (let i = 1; i <= x; i++) {
                            z.push(i)
                        }

                        this.pages = [...z]
                    }
                },
                updateData(res, obj) {
                    let x = common.users.filter(i => i.id != obj.id)

                    common.users = [...x];

                    this.total_users = common.users.length

                    if (this.search_mode) {
                        this.resetSearch()
                    } else {
                        this.paginate()
                    }

                    // total pages
                    this.setPager()

                    common.toast.success(res.message)
                },
                performSearch(str) {
                    return common.users.filter(i => {
                        return i.name.toLowerCase().includes(str) || i.email.toLowerCase()
                            .includes(str)
                    })
                },
                enableModal(x) {
                    document.querySelector(`.form-${x}`).classList.add('active');

                    setTimeout(() => {
                        document.querySelector(`.form-${x} > form input[type=text]`).focus();
                    }, 200)
                },
                shutDownModal() {
                    document.querySelector('.modal.active').classList.remove('active')

                    // reset user
                    this.user_id = null
                    this.user_name = ''
                    this.user_email = ''
                }
            }))
        });
    </script>
@endpush
