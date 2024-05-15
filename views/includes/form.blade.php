<div class="pure-u-1 label">
    <span x-text="label"></span> user:
</div>
<div class="pure-u-1-2">
    <input type="text" class="pure-input-1" name="name" x-model="user_name" placeholder="Name" maxlength="50"
        required />
</div>
<div class="pure-u-1-2">
    <input type="email" class="pure-input-1" name="email" x-model="user_email" placeholder="Email" maxlength="50"
        required />
    <input type="hidden" name="id" x-model="user_id">
</div>
