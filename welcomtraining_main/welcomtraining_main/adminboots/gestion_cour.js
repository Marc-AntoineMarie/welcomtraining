document.getElementById('addCourseBtn').addEventListener('click', function() {
    document.getElementById('courseForm').classList.remove('hidden');
});

document.getElementById('cancelBtn').addEventListener('click', function() {
    document.getElementById('courseForm').classList.add('hidden');
});

document.getElementById('addCourseForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const courseName = document.getElementById('courseName').value;
    const courseDescription = document.getElementById('courseDescription').value;
    const courseDate = document.getElementById('courseDate').value;

    const coursesTable = document.getElementById('courses');
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td>${coursesTable.children.length + 1}</td>
        <td>${courseName}</td>
        <td>${courseDescription}</td>
        <td>${courseDate}</td>
        <td>
            <button class="btn btn-warning btn-sm editBtn">Modifier</button>
            <button class="btn btn-danger btn-sm deleteBtn">Supprimer</button>
        </td>
    `;
    
    coursesTable.appendChild(row);
    
    const notification = document.getElementById('notification');
    notification.innerText = 'Cours ajouté avec succès!';
    notification.classList.remove('hidden');
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
        notification.classList.add('hidden');
    }, 3000);

    document.getElementById('addCourseForm').reset();
    document.getElementById('courseForm').classList.add('hidden');
});
