(function (window, document) {

    const buttons = document.querySelectorAll('.endpoint-link');
    for (const button of buttons) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            let method = event.target.dataset['method'];
            let route = event.target.dataset['route'];
            let body = event.target.dataset['body'];

            let params = {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: method === 'GET' ? undefined : JSON.stringify(body)
            };

            event.target.parentElement.getElementsByClassName('endpoint-request')[0].textContent = body;

            fetch(route, params)
                .then(response => {
                    event.target.parentElement.getElementsByClassName('endpoint-response-status')[0].textContent = response.status;
                    return response.text();
                })
                .then(text => {
                    try {
                        const json = JSON.parse(text); // Try to parse the response as JSON
                        // The response was a JSON object
                        // Do your JSON handling here
                        event.target.parentElement.getElementsByClassName('endpoint-response-body')[0].textContent = JSON.stringify(json, null, 2);
                      } catch(err) {
                        // The response wasn't a JSON object
                        // Do your text handling here
                      }
                })
                .catch(error => console.error('Error creating post:', error));  // Handle errors
        });
    }

    var menu = document.getElementById('menu'),
        rollback,
        WINDOW_CHANGE_EVENT = ('onorientationchange' in window) ? 'orientationchange' : 'resize';

    function toggleHorizontal() {
        menu.classList.remove('closing');
        [].forEach.call(
            document.getElementById('menu').querySelectorAll('.custom-can-transform'),
            function (el) {
                el.classList.toggle('pure-menu-horizontal');
            }
        );
    };

    function toggleMenu() {
        // set timeout so that the panel has a chance to roll up
        // before the menu switches states
        if (menu.classList.contains('is-open')) {
            menu.classList.add('is-closing');
            rollBack = setTimeout(toggleHorizontal, 500);
        }
        else {
            if (menu.classList.contains('closing')) {
                clearTimeout(rollBack);
            } else {
                toggleHorizontal();
            }
        }
        menu.classList.toggle('is-open');
        document.getElementById('toggle').classList.toggle('x');
    };

    function closeMenu() {
        if (menu.classList.contains('is-open')) {
            toggleMenu();
        }
    }

    document.getElementById('toggle').addEventListener('click', function (e) {
        toggleMenu();
        e.preventDefault();
    });

    window.addEventListener(WINDOW_CHANGE_EVENT, closeMenu);
})(this, this.document);
