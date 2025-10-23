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
})(this, this.document);
