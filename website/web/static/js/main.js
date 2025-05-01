(function (window, document) {

    const buttons = document.querySelectorAll('.endpoint');
    for (const button of buttons) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const container = event.target.classList.contains('.endpoint') ? event.target : event.target.closest('.endpoint');
            const method = container.dataset['method'];
            const route = container.dataset['url']
            const body = container.dataset['body'];

            const params = {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: method === 'GET' ? undefined : JSON.stringify(body)
            };

            container.getElementsByClassName('endpoint-request-body')[0].innerHTML = '<div>Request</div><pre>' + body + '</pre>';

            fetch(route, params)
                .then(response => {                   
                    container.getElementsByClassName('endpoint-response-status')[0].textContent = response.status;
                    return response.text();
                })
                .then(text => {
                    try {
                        const json = JSON.parse(text);
                        container.getElementsByClassName('endpoint-response-body')[0].textContent = JSON.stringify(json, null, 2);
                      } catch(err) {
                        // dont't catch error
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
