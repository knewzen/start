define(['require', 'buzz'], function (require, buzz) {
    var Start = (function () {
        function Start() {
            this.duties = [];
        }
        Start.prototype.run = function () {
            var start = this;
            this.search_input = document.getElementById('search');
            this.searching = document.getElementById('searching');
            this.loadDuties();
            document.addEventListener('Start.closeDuty', function (event) {
                if (typeof Android !== 'undefined') {
                    Android.closeDuty(event.detail);
                }
                else {
                    start.closeDuty(event.detail);
                }
            });
            this.search_input.addEventListener('focus', function (event) {
                document.getElementById('stickers').style.display = 'none';
                start.searching.style.display = 'block';
                if (!start.searching.innerHTML) {
                    document.getElementById('search-help').style.display = 'block';
                }
            });
            this.search_input.addEventListener('blur', function (event) {
                var query = start.search_input.value;
                if (!query) {
                    document.getElementById('stickers').style.display = 'block';
                    document.getElementById('search-help').style.display = 'none';
                    start.searching.style.display = 'none';
                }
            });
            this.search_input.addEventListener('keyup', function (event) {
                var query = start.search_input.value;
                start.searchDuties(query);
            });
            this.search_input.addEventListener('paste', function (event) {
                var query = start.search_input.value;
                start.searchDuties(query);
            });
        };
        Start.prototype.loadDuties = function (open, reload) {
            if (open === void 0) { open = null; }
            if (reload === void 0) { reload = false; }
            if (reload) {
                this.duties = [];
                document.getElementById('stickers').innerHTML = '';
            }
            var start = this;
            var request = new XMLHttpRequest();
            var url;
            if (typeof DATA._id !== 'undefined') {
                url = '/duties?_id=' + DATA._id;
            }
            else {
                url = '/duties';
            }
            request.open('GET', url, true);
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    var data = JSON.parse(request.responseText);
                    var duties = data.content;
                    var no_duties = document.getElementById("no-duties");
                    if (duties.length == 0 && no_duties) {
                        no_duties.style.display = 'block';
                    }
                    for (var i = 0; i < duties.length; i++) {
                        (function (i) {
                            var auto_view = open == null;
                            start.addDuty(new Duty(duties[i]), 'current', auto_view);
                        })(i);
                    }
                    if (typeof open == 'object') {
                        start.viewDuty(open);
                    }
                    if (typeof DATA._id === 'undefined') {
                        setInterval(function () {
                            start.loadExtraDuties();
                        }, 10000);
                    }
                }
                else {
                }
            };
            request.onerror = function () {
            };
            request.send();
        };
        Start.prototype.searchDuties = function (query) {
            var start = this;
            if (this.search_request) {
                this.search_request.abort();
            }
            this.search_request = new XMLHttpRequest();
            document.getElementById('searching').innerHTML = '';
            this.search_request.open('GET', '/search?query=' + query, true);
            this.search_request.onload = function () {
                if (start.search_request.status >= 200 && start.search_request.status < 400) {
                    var data = JSON.parse(start.search_request.responseText);
                    var duties = data.content;
                    for (var i = 0; i < duties.length; i++) {
                        (function (i) {
                            start.addDuty(new Duty(duties[i]), 'search');
                        })(i);
                    }
                    if (!start.searching.innerHTML) {
                        document.getElementById('search-help').style.display = 'block';
                    }
                    else {
                        document.getElementById('search-help').style.display = 'none';
                    }
                }
                else {
                }
            };
            this.search_request.onerror = function () {
            };
            this.search_request.send();
        };
        Start.prototype.loadExtraDuties = function () {
            var start = this;
            var request = new XMLHttpRequest();
            request.open('GET', '/extra', true);
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    var data = JSON.parse(request.responseText);
                    if (data.hasOwnProperty('content') && data.content !== null) {
                        var duty = data.content;
                        if (typeof duty === 'object' && Object.keys(duty).length !== 0) {
                            document.getElementById("no-duties").style.display = 'none';
                            start.addDuty(new Duty(duty), 'current');
                            var sound = new buzz.sound(DATA.static + "/sound/extra.mp3");
                            sound.play();
                        }
                    }
                    if (start.duties.length == 0) {
                        document.getElementById("no-duties").style.display = 'block';
                    }
                }
                else {
                }
            };
            request.onerror = function () {
            };
            request.send();
        };
        Start.prototype.postponeDuty = function (id, period) {
            var start = this;
            var request = new XMLHttpRequest();
            request.open('POST', '/duty/postpone/' + id, true);
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    document.getElementById("workspaces").removeChild(document.getElementById("workspace" + id));
                    document.getElementById("stickers").removeChild(document.getElementById("sticker" + id));
                    for (var i = 0; i < start.duties.length; i++) {
                        (function (i) {
                            if (start.duties[i].id === id) {
                                start.duties.splice(i, 1);
                            }
                        })(i);
                    }
                    if (start.duties.length > 0) {
                        start.openWorkspace(start.duties[0]);
                    }
                    else {
                        start.loadExtraDuties();
                    }
                }
                else {
                }
            };
            request.onerror = function () {
            };
            request.send(JSON.stringify({ "period": period }));
        };
        Start.prototype.pickDuty = function (duty) {
            if (!confirm('Приступить к задаче?')) {
                return;
            }
            var start = this;
            var request = new XMLHttpRequest();
            request.open('POST', '/duty/pick/' + duty.id, true);
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    start.loadDuties(duty, true);
                    start.search_input.value = '';
                    start.searching.style.display = 'none';
                    document.getElementById('stickers').style.display = 'block';
                    start.searching.innerHTML = '';
                }
                else {
                }
            };
            request.onerror = function () {
            };
            request.send();
        };
        Start.prototype.closeDuty = function (id) {
            var start = this;
            var request = new XMLHttpRequest();
            request.open('POST', '/duty/close/' + id, true);
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    document.getElementById("workspaces").removeChild(document.getElementById("workspace" + id));
                    document.getElementById("stickers").removeChild(document.getElementById("sticker" + id));
                    for (var i = 0; i < start.duties.length; i++) {
                        (function (i) {
                            if (start.duties[i].id === id) {
                                start.duties.splice(i, 1);
                            }
                        })(i);
                    }
                    if (start.duties.length > 0) {
                        start.openWorkspace(start.duties[0]);
                    }
                    else {
                        start.loadExtraDuties();
                    }
                }
                else {
                }
            };
            request.onerror = function () {
            };
            request.send();
        };
        Start.prototype.viewDuty = function (duty) {
            if (duty === void 0) { duty = null; }
            if (duty == null && this.duties.length > 0) {
                duty = this.duties[0];
            }
            if (duty == null) {
                return;
            }
            var stickers = document.getElementsByClassName('sticker');
            for (var i = 0; i < stickers.length; i++) {
                (function (i) {
                    if (typeof stickers[i] !== 'undefined') {
                        stickers[i].style.background = "white";
                    }
                })(i);
            }
            var active_sticker = document.getElementById("sticker" + duty.id);
            if (active_sticker !== null) {
                document.getElementById("sticker" + duty.id).style.background = "cornsilk";
            }
            this.openWorkspace(duty);
        };
        Start.prototype.addDuty = function (duty, area, auto_view) {
            if (auto_view === void 0) { auto_view = true; }
            this.addSticker(duty, area);
            if (area == 'current') {
                this.duties.push(duty);
            }
            if (auto_view && area == 'current' && this.duties.length === 1) {
                this.openWorkspace(duty);
            }
        };
        Start.prototype.addSticker = function (duty, area) {
            if (area == 'current') {
                var stickers_container = document.getElementById("stickers");
            }
            else {
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
            sticker.innerHTML = "\n                <div style=\"color: #cccccc; font-size: smaller\">" + duty.name + "</div>\n                <div style=\"margin-top: 5px\">" + duty.title + "</div>\n            ";
            if (area == 'current') {
                sticker.addEventListener('click', function () {
                    start.openWorkspace(duty);
                }, false);
            }
            else {
                sticker.addEventListener('click', function () {
                    start.pickDuty(duty);
                }, false);
            }
            stickers_container.appendChild(sticker);
        };
        Start.prototype.setWorkspaceContent = function (id, html) {
            document.getElementById('duty-area' + id).innerHTML = html;
        };
        Start.prototype.openWorkspace = function (duty) {
            var start = this;
            var workspace = document.getElementById("workspace" + duty.id);
            if (workspace == null) {
                var _workspace = document.createElement("div");
                _workspace.id = 'workspace' + duty.id;
                _workspace.setAttribute('class', 'workspace');
                var _buttons_area = document.createElement("div");
                _buttons_area.setAttribute('class', 'buttons-area');
                _workspace.appendChild(_buttons_area);
                var _duty_area = document.createElement("div");
                _duty_area.id = 'duty-area' + duty.id;
                if (duty.iframe !== null) {
                    var _iframe = document.createElement('iframe');
                    _iframe.src = duty.iframe;
                    _iframe.style.width = '100%';
                    _iframe.style.border = 0;
                    _iframe.style.height = ((window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight) - 80) + "px";
                    _duty_area.appendChild(_iframe);
                }
                _workspace.appendChild(_duty_area);
                var _postpone_area = document.createElement("div");
                _postpone_area.id = 'postpone-area' + duty.id;
                _postpone_area.setAttribute('class', 'postpone-area');
                var _postpone_area_list = document.createElement("ul");
                var postpone_options = ['+15 min', '+30 min', '+1 hour', '+2 hour', '+1 day'];
                for (i = 0; i < postpone_options.length; i++) {
                    (function (i) {
                        var _li = document.createElement("li");
                        var _span = document.createElement("span");
                        _span.innerText = postpone_options[i];
                        _span.addEventListener('click', function () {
                            if (confirm('Отложить задачу?')) {
                                start.postponeDuty(duty.id, postpone_options[i]);
                            }
                        }, false);
                        _li.appendChild(_span);
                        _postpone_area_list.appendChild(_li);
                    })(i);
                }
                _postpone_area.appendChild(_postpone_area_list);
                _workspace.appendChild(_postpone_area);
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
                var _postpone_button = document.createElement("button");
                _postpone_button.id = 'postpone-button' + duty.id;
                _postpone_button.innerText = 'Отложить';
                _postpone_button.addEventListener('click', function (event) {
                    var duty_area = document.getElementById("duty-area" + duty.id);
                    if (duty_area.style.display == 'none') {
                        document.getElementById("duty-area" + duty.id).style.display = "block";
                        document.getElementById("postpone-area" + duty.id).style.display = "none";
                        _postpone_button.innerText = 'Отложить';
                    }
                    else {
                        document.getElementById("duty-area" + duty.id).style.display = "none";
                        document.getElementById("postpone-area" + duty.id).style.display = "block";
                        _postpone_button.innerText = 'Назад';
                    }
                }, false);
                _buttons_area.appendChild(_postpone_button);
                document.getElementById("workspaces").appendChild(_workspace);
                if (duty.iframe === null) {
                    require(['/duty/' + duty.id + '?' + Math.random().toString(36).substr(2, 5)], function () { });
                }
            }
            var workspaces = document.getElementsByClassName('workspace');
            var stickers = document.getElementsByClassName('sticker');
            for (var i = 0; i < workspaces.length; i++) {
                (function (i) {
                    workspaces[i].style.display = "none";
                    if (typeof stickers[i] !== 'undefined') {
                        stickers[i].style.background = "white";
                    }
                })(i);
            }
            var active_sticker = document.getElementById("sticker" + duty.id);
            if (active_sticker !== null) {
                document.getElementById("sticker" + duty.id).style.background = "cornsilk";
            }
            document.getElementById("workspace" + duty.id).style.display = "block";
        };
        return Start;
    }());
    var Duty = (function () {
        function Duty(object) {
            this.id = null;
            this.name = null;
            this.title = null;
            this.color = null;
            this.iframe = null;
            this.readonly = null;
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
            if (object.iframe) {
                this.iframe = object.iframe;
            }
            if (object.id) {
                this.readonly = object.readonly;
            }
        }
        return Duty;
    }());
    return new Start();
});
