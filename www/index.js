// fetch todo items from database and add to list
const ul = document.getElementById('task-list');
const form = document.querySelector('form');
const inputName = document.getElementById('task-name');
const inputDetails = document.getElementById('task-details');


$(document).ready(function () {
	var username;
	$.get('check_login.php', function (data) {
		const response = JSON.parse(data);
		username = response.username;
		if (response.loggedin === false) {
			window.location.href = 'login.html';
		} else {
			$('#Welcome').html('Welcome back ' + response.username);
			$('#logout-link').html('(Logout)');
		}
	});

	$('#logout-link').click(function (e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'logout.php',
			success: function () {
				window.location.href = 'login.html';
			}
		});
	});

	// fetch todo items from database and add to list
	$.get('get_todo_items.php', function (data) {
		const todoItems = JSON.parse(data);
		for (let i = 0; i < todoItems.length; i++) {
			const li = document.createElement('li');
			const checkbox = document.createElement('input');
			checkbox.type = 'checkbox';
			checkbox.checked = todoItems[i].completed;

			const link = document.createElement('a');
			link.title = todoItems[i].task_name;
			link.textContent = todoItems[i].task_name;
			link.href = "./forum.html?id=" + encodeURIComponent(todoItems[i].id);

			const spanDetails = document.createElement('span');
			spanDetails.textContent = '  - ' + todoItems[i].task_details;

			const spanCreatedBy = document.createElement('span');
			spanCreatedBy.textContent = 'Created by: ' + todoItems[i].created_by;

			const button = document.createElement('button');
			button.textContent = 'Delete';

			li.appendChild(checkbox);
			li.appendChild(link);
			li.appendChild(spanDetails);
			li.appendChild(spanCreatedBy);
			li.appendChild(button);
			ul.appendChild(li);
		}
	});

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		const taskName = inputName.value.trim();
		const taskDetails = inputDetails.value.trim();

		if (taskName !== '' && taskDetails !== '') {
			// add new todo item to database
			$.post('add_todo_item.php', { taskName: taskName, taskDetails: taskDetails, taskCreatedBy: username }, function (data) {
				console.log(data);
			});

			const link = document.createElement('a');
			$.get('get_todo_items.php', function (data) {
				const todoItems = JSON.parse(data);
				i = todoItems.length - 1;

				link.title = todoItems[i].task_name;
				link.textContent = todoItems[i].task_name;
				link.href = "./forum.html?id=" + encodeURIComponent(todoItems[i].id);
			});

			const li = document.createElement('li');
			const checkbox = document.createElement('input');
			checkbox.type = 'checkbox';

			// const span = document.createElement('span');
			// span.textContent = taskName + ' - ' + taskDetails;

			// const link = document.createElement('a');
			// link.title = todoItems[i].task_name;
			// link.textContent = taskName;
			// link.href = "./forum.html?id=" + encodeURIComponent(todoItems[i].id);

			const spanDetails = document.createElement('span');
			spanDetails.textContent = '  - ' + taskDetails;

			const spanCreatedBy = document.createElement('span');
			spanCreatedBy.textContent = 'Created by: ' + username;

			const button = document.createElement('button');
			button.textContent = 'Delete';

			li.appendChild(checkbox);
			// li.appendChild(span);
			li.appendChild(link);
			li.appendChild(spanDetails);
			li.appendChild(spanCreatedBy);
			li.appendChild(button);
			ul.appendChild(li);
			inputName.value = '';
			inputDetails.value = '';
		}
	});

	ul.addEventListener('click', function (event) {
		if (event.target.tagName === 'BUTTON') {
			const li = event.target.parentNode;
			ul.removeChild(li);

			// delete todo item from database
			const span = li.querySelector('span');
			const taskName = span.textContent.split(' - ')[0];
			$.post('delete_todo_item.php', { taskName: taskName }, function (data) {
				// update the todo items list with the item deleted
				$.get('get_todo_items.php', function (data) {
					const todoItems = JSON.parse(data);
					updateTodoList(todoItems);
				});
			});
		} else if (event.target.tagName === 'INPUT') {
			const li = event.target.parentNode;
			const span = li.querySelector('span');
			const taskName = span.textContent.split(' - ')[0];
			const completed = event.target.checked;

			// update completion status in database
			$.post('update_todo_item.php', { taskName: taskName, completed: completed }, function (data) {
				// update the todo items list with the item updated
				$.get('get_todo_items.php', function (data) {
					const todoItems = JSON.parse(data);
					updateTodoList(todoItems);
				});
			});
		}
	});

});
function updateTodoList(todoItems) {
	ul.innerHTML = '';
	for (let i = 0; i < todoItems.length; i++) {
		const li = document.createElement('li');
		const checkbox = document.createElement('input');
		checkbox.type = 'checkbox';
		checkbox.checked = todoItems[i].completed;

		// const spanTask = document.createElement('span');
		// spanTask.textContent = todoItems[i].task_name + ' - ' + todoItems[i].task_details;

		// const spanCreatedBy = document.createElement('span');
		// spanCreatedBy.textContent = 'Created by: ' + todoItems[i].created_by;

		// const button = document.createElement('button');
		// button.textContent = 'Delete';

		// li.appendChild(checkbox);
		// li.appendChild(spanTask);
		// li.appendChild(spanCreatedBy);
		// li.appendChild(button);
		// ul.appendChild(li);
		const link = document.createElement('a');
		link.title = todoItems[i].task_name;
		link.textContent = todoItems[i].task_name;
		link.href = "./forum.html?id=" + encodeURIComponent(todoItems[i].id);

		const spanDetails = document.createElement('span');
		spanDetails.textContent = '  - ' + todoItems[i].task_details;

		const spanCreatedBy = document.createElement('span');
		spanCreatedBy.textContent = 'Created by: ' + todoItems[i].created_by;

		const button = document.createElement('button');
		button.textContent = 'Delete';

		li.appendChild(checkbox);
		li.appendChild(link);
		li.appendChild(spanDetails);
		li.appendChild(spanCreatedBy);
		li.appendChild(button);
		ul.appendChild(li);
	}
}

