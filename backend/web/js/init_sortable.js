$(document).ready(function () {
    'use strict';

    $("#details-section").hide();

    var byId = function (id) { return document.getElementById(id); },

        loadScripts = function (desc, callback) {
            var deps = [], key, idx = 0;

            for (key in desc) {
                deps.push(key);
            }

            (function _next() {
                var pid,
                    name = deps[idx],
                    script = document.createElement('script');

                script.type = 'text/javascript';
                script.src = desc[deps[idx]];

                pid = setInterval(function () {
                    if (window[name]) {
                        clearTimeout(pid);

                        deps[idx++] = window[name];

                        if (deps[idx]) {
                            _next();
                        } else {
                            callback.apply(null, deps);
                        }
                    }
                }, 30);

                document.getElementsByTagName('head')[0].appendChild(script);
            })()
        },

        console = window.console;


    if (!console.log) {
        console.log = function () {
            alert([].join.apply(arguments, ' '));
        };
    }

    Sortable.create(byId('elements'), {
        group: {
            name: 'words',
            put: false,
            pull: 'clone',
        },
        animation: 150,
        store: {
            get: function (sortable) {
                var order = localStorage.getItem(sortable.options.group);
                return order ? order.split('|') : [];
            },
            set: function (sortable) {
                var order = sortable.toArray();
                localStorage.setItem(sortable.options.group, order.join('|'));
            }
        },
        /*    onAdd: function (evt){
                /!*console.log('onAdd.foo:', [evt.item, evt.from]);*!/
            },
            onUpdate: function (evt){ console.log('onUpdate.foo:', [evt.item, evt.from]); },
            onRemove: function (evt){ console.log('onRemove.foo:', [evt.item, evt.from]); },
            onStart:function(evt){
                console.log('onStart.foo:', [evt.item, evt.from, evt.to]);
            },
            onSort:function(evt){ console.log('onStart.foo:', [evt.item, evt.from]);},
            onEnd: function(evt){

            }*/
    });

    Sortable.create(byId('separators'), {
        group: {
            name: 'words',
            put: false,
            pull: 'clone',
        },
        animation: 150,
        store: {
            get: function (sortable) {
                var order = localStorage.getItem(sortable.options.group);
                return order ? order.split('|') : [];
            },
            set: function (sortable) {
                var order = sortable.toArray();
                localStorage.setItem(sortable.options.group, order.join('|'));
            }
        },
        /*  onAdd: function (evt){
              console.log(evt.item);
          },*/
        /* onUpdate: function (evt){ console.log('onUpdate.foo:', [evt.item, evt.from]); },
         onRemove: function (evt){ console.log('onRemove.foo:', [evt.item, evt.from]); },
         onStart:function(evt){ console.log('onStart.foo:', [evt.item, evt.from]);},
         onSort:function(evt){ console.log('onStart.foo:', [evt.item, evt.from]);},
         onEnd: function(evt){ console.log('onEnd.foo:', [evt.item, evt.from]);}*/
    });

    Sortable.create(byId('submodel'), {
        group: {
            name: 'words',
            put: true,
            pull: false,
        },
        filter: '.js-remove',
        onFilter: function (evt) {
            evt.item.parentNode.removeChild(evt.item);
            $("#details-section").fadeOut(1000);
        },
        animation: 150,
        store: {
            get: function (sortable) {
                var order = localStorage.getItem(sortable.options.group);
                return order ? order.split('|') : [];
            },
            set: function (sortable) {
                var order = sortable.toArray();
                localStorage.setItem(sortable.options.group, order.join('|'));
            }
        },
        onAdd: function (evt){

            evt.item.className = "addedItem";

            evt.item.childNodes[2].setAttribute('style','display: none');

            var span = document.createElement("span");
            var icon = document.createElement("i");
            icon.setAttribute('class','fa fa-trash js-remove');
            span.appendChild(icon);

            evt.item.appendChild(span);

            evt.item.style.cursor = "pointer";

            //Actualizar separadores
            var i = 0;
            var inc = 1;
            var separators = $(".addedItem input[name^='separator']");
            while (i < separators.length) {
                separators[i].name = 'separator-'+inc;
                i++;
                inc++;
            }

            var id = parseFloat(evt.item.id);

            if (evt.from.id === 'elements') {
                evt.item.addEventListener('click', function () {


                    var url = '/lextool/backend/web/sub_model/details?id='+id;

                    $.ajax({
                        url: url,
                        type: 'get',
                        success: function (data) {
                            $("#details-section").fadeIn(1000);
                            $("#details").html(data);
                        }
                    });
                });
            }else {
                $("#details-section").fadeOut(1000);
            }


            //Actualizar los detalles
            if (evt.from.id === 'elements') {
                var url = '/lextool/backend/web/sub_model/details?id=' + id;

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        $("#details-section").fadeIn(1000);
                        $("#details").html(data);
                    }
                });
            }else {
                $("#details-section").fadeOut(1000);
            }

        },
        /*  onUpdate: function (evt){ console.log('onUpdate.foo:', [evt.item, evt.from]); },
          onRemove: function (evt){ console.log('onRemove.foo:', [evt.item, evt.from]); },
          onStart:function(evt){},
          onSort:function(evt){ console.log('onStart.foo:', [evt.item, evt.from]);},
          onEnd: function(evt){ console.log('onEnd.foo:', [evt.item, evt.from]);},*/
    });


    // Background
    document.addEventListener("DOMContentLoaded", function () {
        function setNoiseBackground(el, width, height, opacity) {
            var canvas = document.createElement("canvas");
            var context = canvas.getContext("2d");

            canvas.width = width;
            canvas.height = height;

            for (var i = 0; i < width; i++) {
                for (var j = 0; j < height; j++) {
                    var val = Math.floor(Math.random() * 255);
                    context.fillStyle = "rgba(" + val + "," + val + "," + val + "," + opacity + ")";
                    context.fillRect(i, j, 1, 1);
                }
            }

            el.style.background = "url(" + canvas.toDataURL("image/png") + ")";
        }

        setNoiseBackground(document.getElementsByTagName('body')[0], 50, 50, 0.02);
    }, false);

});