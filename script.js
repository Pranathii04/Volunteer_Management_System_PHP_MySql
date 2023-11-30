// Assuming you have a function to handle flipping posts
let currentPostIndex = 0; // Initialize with the first post

function showPost(index) {
    const posts = document.querySelectorAll('.card');
    posts.forEach((post, i) => {
        post.style.display = i === index ? 'block' : 'none';
    });
}

function prevPost() {
    currentPostIndex = (currentPostIndex - 1 + posts.length) % posts.length;
    showPost(currentPostIndex);
}

function nextPost() {
    currentPostIndex = (currentPostIndex + 1) % posts.length;
    showPost(currentPostIndex);
}

// Initial display
showPost(currentPostIndex);
