document.addEventListener("DOMContentLoaded", () => {
  const userTableBody = document.getElementById("userTableBody");

  // Fetch user data from the backend
  fetch("../php/user.php?action=fetch_users")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch user data.");
      }
      return response.json();
    })
    .then((users) => {
      if (users.length > 0) {
        // Clear table body before appending rows
        userTableBody.innerHTML = "";

        users.forEach((user) => {
          const row = `
            <tr>
              <td>${user.id}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>
                <img src="${user.profile_pic ? user.profile_pic : '../image/weare.jpg'}" alt="Profile Picture" width="50" height="50">
              </td>
            </tr>
          `;
          userTableBody.innerHTML += row;
        });
      } else {
        userTableBody.innerHTML = "<tr><td colspan='4'>No registered users found.</td></tr>";
      }
    })
    .catch((error) => {
      console.error("Error fetching user data:", error);
      userTableBody.innerHTML = "<tr><td colspan='4'>Failed to load users. Please try again later.</td></tr>";
    });
});
