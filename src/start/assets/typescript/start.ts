define(['require'], function (require) {
    class Start {
        constructor() {
            var start = this;
            var fragments = document.getElementsByClassName('fragment');

            for (var i = 0; i < fragments.length; i++) {
                (function(i) {
                    fragments[i].addEventListener('click', function () {
                        start.setFragment(fragments[i].dataset.uri);
                    }, false);
                })(i)
            }
        }

        public run() {
            this.setFeed();
        }

        public setFeed() {

        }

        public setFragment(uri: string) {
            require([uri + '?' + (new Date()).getTime()], function() {});
        }
    }

    class Fragment {
        public getUri() {
            return '';
        }
    }

    return new Start();
});

