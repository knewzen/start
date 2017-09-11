class Start {
    private duties: Array;
    private search_input;
    private searching;
    private search_request;

    constructor() {
        this.duties = [];
    }

    public run() {
        var start = this;

        this.search_input = document.getElementById('search');
        this.searching = document.getElementById('searching');

        //this.loadDuties();

        document.addEventListener('Start.closeDuty', function(event) {
            if (typeof Android !== 'undefined') {
                Android.closeDuty(event.detail);
            } else {
                start.closeDuty(event.detail);
            }
        });

        // this.search_input.addEventListener('focus', function(event) {
        //     document.getElementById('stickers').style.display = 'none';
        //     document.getElementById('no-duties').style.display = 'none';
        //     start.searching.style.display = 'block';
        //
        //     if (!start.searching.innerHTML) {
        //         document.getElementById('search-help').style.display = 'block';
        //     }
        // });
        //
        // this.search_input.addEventListener('blur', function(event) {
        //     var query = start.search_input.value;
        //
        //     if (!query) {
        //         document.getElementById('stickers').style.display = 'block';
        //         document.getElementById('search-help').style.display = 'none';
        //         start.searching.style.display = 'none';
        //
        //         if (start.duties.length == 0) {
        //             document.getElementById("no-duties").style.display = 'block';
        //         }
        //     }
        // });
        //
        // this.search_input.addEventListener('keyup', function(event) {
        //     var query = start.search_input.value;
        //     start.searchDuties(query);
        // });
        //
        // this.search_input.addEventListener('paste', function(event) {
        //     var query = start.search_input.value;
        //     start.searchDuties(query);
        // });

        var toolbars = document.getElementsByClassName('create-duty');

        for (var i = 0; i < toolbars.length; i++) {
            (function(i) {
                if (typeof toolbars[i] !== 'undefined') {
                    toolbars[i].addEventListener('click', function(event) {
                        if (confirm('Создать задачу?')) {
                            start.createDuty(toolbars[i].dataset.id);
                        }
                    });
                }
            })(i)
        }
    }

    public loadDuties(open = null, reload = false) {
        if (reload) {
            this.duties = [];
            document.getElementById('stickers').innerHTML = '';
        }

        var start = this;
        var request = new XMLHttpRequest();
        var url;

        if (typeof DATA._id !== 'undefined') {
            url = '/duties?_id=' + DATA._id;
        } else {
            url = '/duties';
        }

        request.open('GET', url, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = JSON.parse(request.responseText);
                var duties = data.content;
                var no_duties = document.getElementById("no-duties");

                if (duties.length == 0 && no_duties) {
                    no_duties.style.display = 'block';
                }

                for (var i = 0; i < duties.length; i++) {
                    (function(i) {
                        var auto_view = open == null;
                        start.addDuty(new Duty(duties[i]), 'current', auto_view);
                    })(i)
                }

                if (typeof open == 'object') {
                    start.viewDuty(open);
                }

                if (typeof DATA._id === 'undefined') {
                    setInterval(function() {
                        start.loadExtraDuties();
                    }, 10000);
                }
            } else {
            }
        };

        request.onerror = function() {
        };

        request.send();
    }

    public searchDuties(query) {
        var start = this;

        if (this.search_request) {
            this.search_request.abort();
        }

        this.search_request = new XMLHttpRequest();

        document.getElementById('searching').innerHTML = '';

        this.search_request.open('GET', '/search?query=' + query, true);

        this.search_request.onload = function() {
            if (start.search_request.status >= 200 && start.search_request.status < 400) {
                var data = JSON.parse(start.search_request.responseText);
                var duties = data.content;

                for (var i = 0; i < duties.length; i++) {
                    (function(i) {
                        start.addDuty(new Duty(duties[i]), 'search');
                    })(i)
                }

                if (!start.searching.innerHTML) {
                    document.getElementById('search-help').style.display = 'block';
                } else {
                    document.getElementById('search-help').style.display = 'none';
                }
            } else {
            }
        };

        this.search_request.onerror = function() {
        };

        this.search_request.send();
    }

    public loadExtraDuties() {
        var start = this;

        var request = new XMLHttpRequest();
        request.open('GET', '/extra', true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = JSON.parse(request.responseText);

                if (data.hasOwnProperty('content') && data.content !== null) {
                    var duties = data.content;

                    for (var i = 0; i < duties.length; i++) {
                        (function(i) {
                            var duty = duties[i];

                            if (typeof duty === 'object' && Object.keys(duty).length !== 0) {
                                document.getElementById("no-duties").style.display = 'none';

                                start.addDuty(new Duty(duty), 'current');

                                var sound = new buzz.sound(DATA.static + "/sound/extra.mp3");
                                sound.play();
                            }
                        })(i)
                    }
                }

                if (start.duties.length == 0 && document.getElementById('stickers').style.display != 'none') {
                    document.getElementById("no-duties").style.display = 'block';
                }
            } else {
            }
        };

        request.onerror = function() {
        };

        request.send();
    }

    public createDuty(activity_id: number) {
        var start = this;
        var request = new XMLHttpRequest();
        request.open('POST', '/duty/create', true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = JSON.parse(request.responseText);

                if (data.hasOwnProperty('content') && data.content !== null) {
                    var duty = data.content;

                    if (typeof duty === 'object' && Object.keys(duty).length !== 0) {
                        document.getElementById("no-duties").style.display = 'none';

                        start.addDuty(new Duty(duty), 'current');
                    }
                }
            } else {
            }
        };

        request.onerror = function() {
        };

        request.send(JSON.stringify({"activity_id": activity_id}));
    }

    public postponeDuty(id: number, period?: string, datetime?: string) {
        var start = this;
        var request = new XMLHttpRequest();
        request.open('POST', '/duty/postpone/' + id, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                document.getElementById("workspaces").removeChild(document.getElementById("workspace" + id));
                document.getElementById("stickers").removeChild(document.getElementById("sticker" + id));

                document.getElementById('stickers-column').className = "visible";
                document.getElementById('workspaces').className = "invisible";

                for (var i = 0;  i < start.duties.length; i++) {
                    (function(i) {
                        if(start.duties[i].id === id) {
                            start.duties.splice(i, 1);
                        }
                    })(i)
                }

                if (start.duties.length > 0) {
                    start.openWorkspace(start.duties[0]);
                } else {
                    start.loadExtraDuties();
                }
            } else {
            }
        };

        request.onerror = function() {
        };

        var body = {};

        if (period) {
            body.period = period;
        }

        if (datetime) {
            body.datetime = datetime;
        }

        request.send(JSON.stringify(body));
    }

    public pickDuty(duty: Duty) {
        if (!confirm('Приступить к задаче?')) {
            return;
        }

        var start = this;
        var request = new XMLHttpRequest();
        request.open('POST', '/duty/pick/' + duty.id, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                start.loadDuties(duty, true);
                start.search_input.value = '';
                start.searching.style.display = 'none';
                document.getElementById('stickers').style.display = 'block';
                start.searching.innerHTML = '';
                document.getElementById('stickers-column').className = "invisible";
                document.getElementById('workspaces').className = "visible";
            } else {
            }
        };

        request.onerror = function() {
        };

        request.send();
    }

    public closeDuty(id: number) {
        var start = this;
        var request = new XMLHttpRequest();
        request.open('POST', '/duty/close/' + id, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                document.getElementById("workspaces").removeChild(document.getElementById("workspace" + id));
                document.getElementById("stickers").removeChild(document.getElementById("sticker" + id));

                document.getElementById('stickers-column').className = "visible";
                document.getElementById('workspaces').className = "invisible";

                for (var i = 0;  i < start.duties.length; i++) {
                    (function(i) {
                        if(start.duties[i].id == id) {
                            start.duties.splice(i, 1);
                        }
                    })(i)
                }

                if (start.duties.length > 0) {
                    start.openWorkspace(start.duties[0]);
                } else {
                    start.loadExtraDuties();
                }
            } else {
            }
        };

        request.onerror = function() {
        };

        request.send();
    }

    public commentDuty(duty: Duty, comment: string) {
        var start = this;
        var request = new XMLHttpRequest();
        request.open('POST', '/duty/comment/' + duty.id, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var comment_area = document.getElementById("comment-area" + duty.id);
                var comment_button = document.getElementById("comment-button" + duty.id);
                var comment_text_area = document.getElementById("comment-text-area" + duty.id);
                comment_area.style.display = 'none';
                comment_text_area.innerText = comment;
                comment_button.innerText = 'Комментарий';
                comment_button.className = '';
                duty.comment = comment;

                if (!comment) {
                    comment_text_area.style.display = 'none';
                } else {
                    comment_text_area.style.display = 'block';
                }

                if (duty.readonly) {
                    document.getElementById("close-button" + duty.id).style.display = "initial";
                }

                document.getElementById("postpone-button" + duty.id).style.display = "initial";
            } else {
                alert('Не удалось сохранить комментарий');
            }
        };

        request.onerror = function() {
            alert('Не удалось сохранить комментарий');
        };

        request.send(JSON.stringify({"comment": comment}));
    }

    public viewDuty(duty = null) {
        if (duty == null && this.duties.length > 0) {
            duty = this.duties[0];
        }

        if (duty == null) {
            return;
        }

        var stickers = document.getElementsByClassName('sticker');

        for (var i = 0; i < stickers.length; i++) {
            (function(i) {
                if (typeof stickers[i] !== 'undefined') {
                    stickers[i].style.background = "white";
                }
            })(i)
        }

        var active_sticker = document.getElementById("sticker" + duty.id);

        if (active_sticker !== null) {
            document.getElementById("sticker" + duty.id).style.background = "cornsilk";
        }

        this.openWorkspace(duty);
    }

    public addDuty(duty: Duty, area: string, auto_view = true) {
        this.addSticker(duty, area);

        if (area == 'current') {
            this.duties.push(duty);
        }

        if (auto_view && area == 'current' && this.duties.length === 1) {
            this.openWorkspace(duty);
        }
    }

    public addSticker(duty: Duty, area: string) {
        if (area == 'current') {
            var stickers_container = document.getElementById("stickers");
        } else {
            var stickers_container = document.getElementById("searching");
        }

        if (stickers_container == null) {
            return;
        }

        var start = this;

        var sticker = document.createElement("div");

        if (duty.color != null) {
            sticker.style['border-left'] = '20px solid ' + duty.color;
        }

        sticker.id = 'sticker' + duty.id;
        sticker.setAttribute('class', 'sticker');

        var inner_html = `
            <div style="color: #cccccc; font-size: smaller">` + duty.name + `</div>
            <div style="margin-top: 5px">` + duty.title + `</div>
        `;

        if (duty.tags.length > 0) {
            inner_html += `<div style="margin-top: 5px; font-size: smaller">` + duty.tags.join(', ') + `</div>`;
        }

        sticker.innerHTML = inner_html;

        if (area == 'current') {
            sticker.addEventListener('click', function () {
                document.getElementById('stickers-column').className = "invisible";
                document.getElementById('workspaces').className = "visible";
                start.openWorkspace(duty);
            }, false);
        } else {
            sticker.addEventListener('click', function () {
                start.pickDuty(duty);
            }, false);
        }

        stickers_container.appendChild(sticker);
    }

    public setWorkspaceContent(id: number, html: string) {
        document.getElementById('duty-area' + id).innerHTML = html;
    }

    public openWorkspace(duty: Duty) {
        var start = this;
        var workspace = document.getElementById("workspace" + duty.id);

        if (workspace == null) {
            var _workspace = document.createElement("div");
            _workspace.id = 'workspace' + duty.id;
            _workspace.setAttribute('class', 'workspace');

            var _buttons_area = document.createElement("div");
            _buttons_area.setAttribute('class', 'buttons-area');

            _workspace.appendChild(_buttons_area);

            var _comment_text_area = document.createElement("div");
            _comment_text_area.id = 'comment-text-area' + duty.id;
            _comment_text_area.setAttribute('class', 'comment-text-area');
            _comment_text_area.innerText = duty.comment;

            if (!duty.comment) {
                _comment_text_area.style.display = 'none';
            } else {
                _comment_text_area.style.display = 'block';
            }

            _workspace.appendChild(_comment_text_area);

            var _comment_area = document.createElement("div");
            _comment_area.id = 'comment-area' + duty.id;
            _comment_area.setAttribute('class', 'comment-area');
            _comment_area.style.display = 'none';

            var _comment_textarea = document.createElement("textarea");
            _comment_textarea.id = 'comment-textarea' + duty.id;
            _comment_textarea.rows = 5;
            _comment_textarea.placeholder = 'Комментарий';
            _comment_textarea.value = duty.comment;

            _comment_area.appendChild(_comment_textarea);
            _workspace.appendChild(_comment_area);

            var _duty_area = document.createElement("div");
            _duty_area.id = 'duty-area' + duty.id;
            _duty_area.className = 'duty-area';

            var _iframe = document.createElement('iframe');
            _iframe.src = duty.iframe;
            _iframe.style.width = '100%';
            _iframe.style.border = 0;
            _iframe.style.height = ((window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight) - 80) + "px";

            _duty_area.appendChild(_iframe);

            _workspace.appendChild(_duty_area);

            if (duty.writable) {
                var _comment_button = document.createElement("button");
                _comment_button.id = 'comment-button' + duty.id;
                _comment_button.innerText = 'Комментарий';

                _comment_button.addEventListener('click', function () {
                    start.addComment(duty, _comment_button);
                }, false);

                _comment_textarea.addEventListener('keypress', function (e) {console.log(e);
                    if (e.ctrlKey && e.keyCode == 13) {
                        start.addComment(duty, _comment_button);
                    }
                }, false);

                _buttons_area.appendChild(_comment_button);
            }

            if (duty.readonly) {
                var _close_button = document.createElement("button");
                _close_button.id = 'close-button' + duty.id;
                _close_button.innerText = 'Закрыть';

                _close_button.addEventListener('click', function () {
                    if (confirm('Закрыть задачу?')) {
                        start.closeDuty(duty.id);
                    }
                }, false);

                _buttons_area.appendChild(_close_button);
            }

            if (duty.postponable) {
                var _postpone_area = document.createElement("div");
                _postpone_area.id = 'postpone-area' + duty.id;
                _postpone_area.setAttribute('class', 'postpone-area');

                var _postpone_area_list = document.createElement("ul");
                var postpone_options = {
                    '+15 min': 'На 15 минут',
                    '+30 min': 'На 30 минут',
                    '+1 hour': 'На 1 час',
                    '+2 hour': 'На 2 часа',
                    '+1 day': 'На 1 день',
                    '+2 day': 'На 2 дня'
                };

                var postpone_options_keys = Object.keys(postpone_options);

                for (i = 0; i < postpone_options_keys.length; i++) {
                    (function(i) {
                        var _li = document.createElement("li");
                        var _span = document.createElement("span");
                        _span.innerText = postpone_options[postpone_options_keys[i]];

                        _span.addEventListener('click', function () {
                            if (confirm('Отложить задачу?')) {
                                start.postponeDuty(duty.id, postpone_options_keys[i]);
                            }
                        }, false);

                        _li.appendChild(_span);

                        _postpone_area_list.appendChild(_li);
                    })(i)
                }

                var _input_date = document.createElement("input");
                _input_date.type = 'date';

                var _input_time = document.createElement("input");
                _input_time.type = 'time';
                _input_time.value = '09:00';

                var _datetime_save_button = document.createElement("button");
                _datetime_save_button.innerText = 'Сохранить';
                _datetime_save_button.className = 'primary';

                _datetime_save_button.addEventListener('click', function () {
                    if (_input_date.value && _input_time.value && confirm('Отложить задачу?')) {
                        start.postponeDuty(duty.id, null, _input_date.value + " " + _input_time.value);
                    }
                }, false);

                var _li = document.createElement("li");
                _li.appendChild(_input_date);
                _li.appendChild(_input_time);
                _li.appendChild(_datetime_save_button);

                _postpone_area_list.appendChild(_li);

                _postpone_area.appendChild(_postpone_area_list);
                _workspace.appendChild(_postpone_area);

                var _back_button = document.createElement("button");
                _back_button.id = 'back-button' + duty.id;
                _back_button.className = 'back-button';
                _back_button.innerText = 'Назад';

                _back_button.addEventListener('click', function () {
                    document.getElementById('stickers-column').className = "visible";
                    document.getElementById('workspaces').className = "invisible";
                }, false);

                _buttons_area.appendChild(_back_button);

                var _postpone_button = document.createElement("button");
                _postpone_button.id = 'postpone-button' + duty.id;
                _postpone_button.innerText = 'Отложить';

                _postpone_button.addEventListener('click', function (event) {
                    var duty_area = document.getElementById("duty-area" + duty.id);

                    if (duty_area.style.display == 'none') {
                        document.getElementById("duty-area" + duty.id).style.display = "block";
                        document.getElementById("postpone-area" + duty.id).style.display = "none";

                        if (duty.comment) {
                            document.getElementById("comment-text-area" + duty.id).style.display = "block";
                        }

                        if (duty.readonly) {
                            document.getElementById("close-button" + duty.id).style.display = "initial";
                        }

                        if (duty.writable) {
                            document.getElementById("comment-button" + duty.id).style.display = "initial";
                        }

                        _postpone_button.innerText = 'Отложить';
                    } else {
                        document.getElementById("duty-area" + duty.id).style.display = "none";
                        document.getElementById("postpone-area" + duty.id).style.display = "block";
                        document.getElementById("comment-text-area" + duty.id).style.display = "none";
                        document.getElementById("comment-button" + duty.id).style.display = "none";
                        document.getElementById("close-button" + duty.id).style.display = "none";
                        _postpone_button.innerText = 'Отмена';
                    }
                }, false);

                _buttons_area.appendChild(_postpone_button);
            }

            document.getElementById("workspaces").appendChild(_workspace);
        }

        var workspaces = document.getElementsByClassName('workspace');
        var stickers = document.getElementsByClassName('sticker');

        for (var i = 0; i < workspaces.length; i++) {
            (function(i) {
                workspaces[i].style.display = "none";

                if (typeof stickers[i] !== 'undefined') {
                    stickers[i].style.background = "white";
                }
            })(i)
        }

        var active_sticker = document.getElementById("sticker" + duty.id);

        if (active_sticker !== null) {
            document.getElementById("sticker" + duty.id).style.background = "cornsilk";
        }

        document.getElementById("workspace" + duty.id).style.display = "block";
    }

    protected addComment(duty: Duty, comment_button) {
        var start = this;
        var comment_area = document.getElementById("comment-area" + duty.id);
        var comment_text_area = document.getElementById("comment-text-area" + duty.id);
        var comment_textarea = document.getElementById("comment-textarea" + duty.id);

        if (comment_area.style.display == 'none') {
            comment_area.style.display = 'block';
            comment_text_area.style.display = 'none';
            comment_button.innerText = 'Сохранить';
            comment_button.className = 'primary';
            document.getElementById("postpone-button" + duty.id).style.display = "none";
            document.getElementById("close-button" + duty.id).style.display = "none";
        } else {
            start.commentDuty(duty, comment_textarea.value);
        }
    }
}

class Duty {
    id: number = null;
    name: string = null;
    title: string = null;
    color: string = null;
    tags: Array = [];
    comment: string = null;
    iframe: string = null;
    readonly: boolean = null;
    writable: boolean = null;
    postponable: boolean = null;

    constructor(object: any) {
        if (object.id) {
            this.id = object.id;
        }

        if (object.name) {
            this.name = object.name;
        }

        if (object.title) {
            this.title = object.title;
        }

        if (object.color) {
            this.color = object.color;
        }

        if (object.tags) {
            this.tags = object.tags;
        }

        if (object.comment) {
            this.comment = object.comment;
        }

        if (object.iframe) {
            this.iframe = object.iframe;
        }

        if (object.readonly) {
            this.readonly = object.readonly;
        }

        if (object.writable) {
            this.writable = object.writable;
        }

        if (object.postponable) {
            this.postponable = object.postponable;
        }
    }
}