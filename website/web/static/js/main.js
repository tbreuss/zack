(function (window, document) {
    document.addEventListener('DOMContentLoaded', () => {
        const currentHost = window.location.hostname;
        document.querySelectorAll('a').forEach(link => {
            if (link.hostname && link.hostname !== currentHost) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
    });

    const buttons = document.querySelectorAll('.endpoint');
    for (const button of buttons) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const container = event.target.classList.contains('endpoint') ? event.target : event.target.closest('.endpoint');

            if (!container.classList.contains('endpoint--opened')) {
                for (const el of document.getElementsByClassName('endpoint--opened')) {
                    el.classList.remove('endpoint--opened');
                    el.getElementsByClassName('endpoint-request-body')[0].innerHTML = '';
                    el.getElementsByClassName('endpoint-response-status')[0].textContent = '';
                    el.getElementsByClassName('endpoint-response-body')[0].textContent = '';
                }
            };

            const method = container.dataset['method'];
            const route = container.dataset['url']
            const body = container.dataset['body'];
            const hasRequestBody = method === 'POST' || method === 'PUT' || method === 'PATCH'

            const params = {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: hasRequestBody ? JSON.parse(body) : undefined
            };

            if (hasRequestBody) {
                const json = JSON.parse(body);
                container.getElementsByClassName('endpoint-request-body')[0].innerHTML =
                    '<p>'
                    + 'Request: ' + method + ' ' + route
                    + '</p>'
                    + '<pre><code class="language-json hljs">'
                    + JSON.stringify(json, null, 2)
                    + '</code></pre>';
            } else {
                container.getElementsByClassName('endpoint-request-body')[0].innerHTML =
                    '<p>'
                    + 'Request: ' + method + ' ' + route
                    + '</p>';
            }

            fetch(route, params)
                .then(response => {
                    container.classList.add('endpoint--opened');
                    container.getElementsByClassName('endpoint-response-status')[0].innerHTML = '<p>Response: ' + response.status + '</p>';
                    return response.text();
                })
                .then(text => {
                    try {
                        const json = JSON.parse(text);
                        container.getElementsByClassName('endpoint-response-body')[0].innerHTML = '<pre><code class="language-json hljs">' + JSON.stringify(json, null, 2) + '</code></pre>';
                    } catch(err) {
                        // dont't catch error
                    }
                })
                .catch(error => console.error('Error creating post:', error));  // Handle errors
        });
    }
})(this, this.document);
