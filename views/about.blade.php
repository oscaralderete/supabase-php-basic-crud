<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        :root {
            --bg-theme: #669bbc;
            --bg-green: teal;
            --bg-red: crimson;
            --bg-yellow: #ff9;
            --bg-dark: #222;
            --bg-contrast: #fff;
        }

        body {
            margin: 0
        }

        .colored {
            background-color: var(--bg-theme);
            height: 50vh;
        }

        .colored.red {
            background-color: var(--bg-red);
        }

        .colored.green {
            background-color: var(--bg-green)
        }

        .content {
            height: 50vh;
        }

        header,
        footer {
            margin: 0;
            padding: 1rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: transparent;
            position: sticky;
            top: 0;
            box-shadow: 0 0 .5rem #555;
            font-weight: bold;
        }

        footer {
            height: 50vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* the fun starts here!  */
        svg {
            height: 2rem;
            width: auto;
            fill: var(--bg-theme);
        }

        svg.contrast {
            fill: #fff
        }

        svg.red {
            fill: var(--bg-yellow);
        }

        svg.green {
            fill: var(--bg-dark);
        }

        .red,
        .green {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
    </style>
</head>

<body>
    <header>
        <span>
            <svg id="logo" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path
                    d="M0 32l34.9 395.8L191.5 480l157.6-52.2L384 32H0zm308.2 127.9H124.4l4.1 49.4h175.6l-13.6 148.4-97.9 27v.3h-1.1l-98.7-27.3-6-75.8h47.7L138 320l53.5 14.5 53.7-14.5 6-62.2H84.3L71.5 112.2h241.1l-4.4 47.7z" />
            </svg>
        </span>

        <aside>
            Home | About | Contact | Etc
        </aside>
    </header>

    <section>
        <div class="content">
            <p>Dynamically change color of floating logo when it's over same background color element.</p>
            <p>Scroll keeping your eye on the page logo...</p>
            <p>Works fine on Firefox, Brave & Chrome. It gets a little silly with Edge but who use it these days, except
                for Bill Gates? 😂😂😂</p>
        </div>
        <div class="colored test"></div>
        <div class="content"></div>
        <div class="colored"></div>
        <div class="content"></div>
        <div data-bg_color="red" class="colored red">
            <span>Logo color: <b>yellow</b></span>
            <span>Thanks to: data-bg_color="red"</span>
        </div>
        <div class="content"></div>
        <div class="colored"></div>
        <div class="content"></div>
        <div data-bg_color="green" class="colored green">
            <span>Logo color: <b>black</b></span>
            <span>Thanks to: data-bg_color="green"</span>
        </div>
        <div class="content"></div>
        <div class="colored"></div>
    </section>

    <footer>
        <p>This footer is huge!</p>
        <span>Developed by <a href="https://oscaralderete.com" target="_blank">Oscar
                Alderete</a></span>
        <span>Check it on Codepen: <a href="https://codepen.io/oscaralderete/pen/ZENGavX"
                target="_blank">https://codepen.io/oscaralderete/pen/ZENGavX</a>.</span>
    </footer>

    <script defer>
        const logo = document.getElementById('logo'),
            divs = document.getElementsByClassName('colored');

        let positions = [];
        for (let i = 0; i < divs.length; i++) {
            const x = divs[i].getBoundingClientRect();

            positions.push({
                start: x.top,
                end: (x.top + x.height),
                bg_color: divs[i].dataset.bg_color ? divs[i].dataset.bg_color : 'contrast',
            })
        }

        window.addEventListener("scroll", function(event) {
            var top = this.scrollY;

            positions.forEach(i => {
                if (top >= i.start) {
                    if (top - i.start >= 0 && top - i.end < 0) {
                        logo.classList.add(i.bg_color)
                    } else {
                        logo.classList.remove('contrast', 'red', 'green')
                    }
                } else if (top < positions[0].start) {
                    logo.classList.remove('contrast', 'red', 'green')
                }
            })
        }, false);
    </script>
</body>

</html>
