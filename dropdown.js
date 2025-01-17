document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton2 = document.getElementById('dropdownDefaultButton2');
    const dropdownLinks2 = document.querySelectorAll('#dropdown2 a'); // Select all <a> tags within #dropdown2

    const dropdownButton = document.getElementById('dropdownDefaultButton');
    const dropdownLinks = document.querySelectorAll('#dropdown a'); // Select all <a> tags within #dropdown2

    dropdownLinks2.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor behavior
            const selectedValue = link.textContent.trim(); // Get the text content of the clicked link
            dropdownButton2.innerHTML = `${selectedValue} 
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>`;
        });
    });

    dropdownLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default anchor behavior
            const selectedValue = link.textContent.trim(); // Get the text content of the clicked link
            dropdownButton.innerHTML = `${selectedValue} 
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>`;
        });
    });
});