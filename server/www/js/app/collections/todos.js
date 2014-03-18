define(['backbone', 'models/Todo'],
    function (Backbone, Todo) {
        var Todos = Backbone.Collection.extend({
            model: Todo,
            comparator: function (m) {
                return m.get('created_on');
            },
            url: '../api/index.php/todo',
            initialize: function () {
                this.fetch();
            }
        });

        var todos = new Todos();
        return todos;
    });