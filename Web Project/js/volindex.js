document.addEventListener("DOMContentLoaded", () => {
  const profilePicForm = document.getElementById("profile-pic-form");
  const profileInfoForm = document.getElementById("profile-info-form");
  const createPostForm = document.getElementById("create-post-form");

  const profilePic = document.getElementById("profile-pic");
  const postsContainer = document.getElementById("posts");

  // Fetch and display user data on load
  fetchUserProfile();
  fetchPosts();

  // Handle Profile Picture Upload
  document.getElementById("profile-pic-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("../php/user_actions.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update the profile picture dynamically
          profilePic.src = data.profile_pic + "?t=" + new Date().getTime(); // Prevent caching
          alert(data.message);
        } else {
          alert(data.message);
        }
      })
      .catch((error) => console.error("Error:", error));
  });

  // Handle Profile Info Update
  document.getElementById("update-profile-info").addEventListener("click", () => {
    const formData = new FormData(profileInfoForm);
    formData.append("action", "update_profile");

    fetch("../php/user_actions.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Profile updated successfully.");
        } else {
          alert(data.message);
        }
      })
      .catch((error) => console.error("Error updating profile:", error));
  });






// Handle Post Creation
document.getElementById("submit-post").addEventListener("click", () => {
  const createPostForm = document.getElementById("create-post-form");
  const formData = new FormData(createPostForm);
  formData.append("action", "create_post");

  fetch("../php/post_handler.php", {
      method: "POST",
      body: formData,
  })
      .then((response) => response.json())
      .then((data) => {
          if (data.success) {
              fetchPosts(); // Refresh posts after creating a new one
              alert("Post created successfully.");
          } else {
              alert(data.message);
          }
      })
      .catch((error) => {
          console.error("Error creating post:", error);
          alert("An error occurred. Please try again.");
      });
});

// Fetch and Display Posts
function fetchPosts() {
  const postsContainer = document.getElementById("posts");

  fetch("../php/post_handler.php?action=fetch_posts")
      .then((response) => response.json())
      .then((posts) => {
          postsContainer.innerHTML = ""; // Clear current posts
          posts.forEach((post) => {
              const postElement = document.createElement("div");
              postElement.className = "post-item";
              postElement.innerHTML = `
                  ${post.image ? `<img src="${post.image}" alt="Post Image" class="post-image">` : ""}
                  <p>${post.content}</p>
                  <small>${post.created_at}</small>
              `;
              postsContainer.appendChild(postElement);
          });
      })
      .catch((error) => {
          console.error("Error fetching posts:", error);
          alert("An error occurred while fetching posts.");
      });
}

// Initialize posts on page load
document.addEventListener("DOMContentLoaded", () => {
  fetchPosts();
});

  // Fetch and Populate User Profile
  function fetchUserProfile() {
    fetch("../php/user_actions.php?action=fetch_profile")
      .then((response) => response.json())
      .then((data) => {
        if (data.name) {
          document.getElementById("name").value = data.name;
        }
        if (data.phone) {
          document.getElementById("phone").value = data.phone;
        }
        if (data.profile_pic) {
          profilePic.src = data.profile_pic + "?t=" + new Date().getTime(); // Prevent caching
        } else {
          profilePic.src = "../image/edu.jpg"; // Fallback to default picture
        }
      })
      .catch((error) => console.error("Error fetching profile:", error));
  }
});







document.addEventListener("DOMContentLoaded", () => {
  // Fetch user data from the server
  fetch('../php/get_user_data.php') // Adjust the path if necessary
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.error) {
        displayError(data.error);
      } else {
        // Populate the name and email into the HTML
        document.getElementById('user-name').textContent = data.name;
        document.getElementById('user-email').textContent = data.email;
      }
    })
    .catch(error => {
      console.error("Error fetching user data:", error);
      displayError("Could not connect to the server. Please try again.");
    });
});

// Function to display error messages
function displayError(message) {
  const errorMessage = document.getElementById('error-message');
  errorMessage.textContent = message;
  errorMessage.style.display = "block";
  document.getElementById('user-name').textContent = "N/A";
  document.getElementById('user-email').textContent = "N/A";
}





