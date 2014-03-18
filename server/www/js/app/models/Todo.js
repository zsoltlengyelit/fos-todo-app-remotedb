define(['backbone'],
function (Backbone) {
  var Todo = Backbone.Model.extend({
      defaults: {
        task: 'my task',
        completed: false,
        created_on: new Date().getTime()
      },

      validate: function (attrs) {
        if (!attrs.task) {
            return 'Task can\'t be empty';
        }
      }
  });

  return Todo;
});