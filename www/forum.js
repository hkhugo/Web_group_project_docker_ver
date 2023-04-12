// fetch todo items from database and add to list
const urlParams = new URLSearchParams(window.location.search);
const message = document.getElementById('message');
const taskid = urlParams.get('id');
const inputName = document.getElementById('task-name');
const inputDetails = document.getElementById('task-details');
const d = document.getElementById('task-list');
var username;


$(document).ready(function () {
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

  // Add forum-specific code here
  // $.get('get_post_items.php?id=' + encodeURIComponent(taskid), function (data) {
  //     const response = JSON.parse(data);
  //     console.log(response)
  //     const taskname = response.taskname;
  //     console.log(taskname)
  //     const posts = response.posts;

  //     $('#Taskname').html(taskname);

  //     for (let i = 0; i < posts.length; i++) {
  //         const div = document.createElement('div');
  //         div.className = "message";

  //         const spanUsename = document.createElement('span');
  //         spanUsename.className = "username"
  //         spanUsename.textContent = posts[i].user_name;

  //         const spanTimestamp = document.createElement('span');
  //         spanTimestamp.className = "timestamp"
  //         spanTimestamp.textContent = posts[i].post_date;

  //         const divContent = document.createElement('div');
  //         divContent.className = "content"
  //         divContent.textContent = posts[i].post_content;

  //         div.appendChild(spanUsename);
  //         div.appendChild(spanTimestamp);
  //         div.appendChild(divContent);
  //         message.appendChild(div);
  //     }
  // });

  $('#submit-post').on('click', function () {
    postMessage();
  });

// Retrieve posts from database
function getPosts() {
  $.get('get_post_items.php?id=' + encodeURIComponent(taskid), function (data) {
    console.log(data)
    const response = JSON.parse(data);
    const taskname = response.taskname;
    let posts = response.posts;

    // sort posts array based on post_date in ascending order
    posts.sort((a, b) => new Date(a.post_date) - new Date(b.post_date));

    $('#Taskname').html(taskname);

    for (let i = 0; i < posts.length; i++) {
      const div = document.createElement('div');
      div.className = "message";

      const spanUsename = document.createElement('span');
      spanUsename.className = "username";
      spanUsename.textContent = posts[i].user_name;

      const spanTimestamp = document.createElement('span');
      spanTimestamp.className = "timestamp";
      spanTimestamp.textContent = posts[i].post_date;

      const divContent = document.createElement('div');
      divContent.className = "content";
      divContent.textContent = posts[i].post_content;

      div.appendChild(spanUsename);
      div.appendChild(spanTimestamp);
      div.appendChild(divContent);
      message.appendChild(div);
    }
  });
}

  function postMessage() {
    const postContent = $('#post-content').val();
    if (!postContent) {
      alert('Please enter a message before submitting!');
      return;
    }

    $.post('send_message.php', {
      taskid: taskid,
      post_content: postContent,
      username: username
    }, function (data) {
      console.log(data)
      const response = JSON.parse(data);
      // Append the new post to the message container and clear the message input field
      const div = document.createElement('div');
      div.className = "message";

      console.log(response.user_name)
      const spanUsename = document.createElement('span');
      spanUsename.className = "username";
      spanUsename.textContent = response.user_name;

      const spanTimestamp = document.createElement('span');
      spanTimestamp.className = "timestamp";
      spanTimestamp.textContent = response.post_date;

      const divContent = document.createElement('div');
      divContent.className = "content";
      divContent.textContent = response.post_content;

      div.appendChild(spanUsename);
      div.appendChild(spanTimestamp);
      div.appendChild(divContent);
      message.appendChild(div);

      $('#post-content').val('');
    });
  }
  getPosts();
});
