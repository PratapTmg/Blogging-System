document.addEventListener('DOMContentLoaded', function() {
    e.preventDefault();
    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('resultsContainer');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value;

        if (query.length > 0) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `search.php?q=${query}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    resultsContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        } else {
            resultsContainer.innerHTML = '';
        }
    });
});