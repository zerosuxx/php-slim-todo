CREATE USER 'todo_app'@'%' IDENTIFIED BY 'todo_app';
GRANT ALL PRIVILEGES ON todo_app.* TO 'todo_app'@'%';
GRANT ALL PRIVILEGES ON todo_app_test.* TO 'todo_app'@'%';