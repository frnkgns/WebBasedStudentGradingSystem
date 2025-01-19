document.addEventListener("DOMContentLoaded", function() {

    hideYearLevelTables();
    SubjectDropdown();

    function hideYearLevelTables(){
        const dropdownButton = document.getElementById("dropdownDefaultButton");
        const dropdownMenu = document.getElementById("dropdown");
        const form1 = document.getElementById('firstForm');
        const form2 = document.getElementById('secondForm');
        const form3 = document.getElementById('thirdForm');
        const form4 = document.getElementById('fourthForm');

        // hide all tables but view the first table
        const allForms = [form1, form2, form3, form4];
        allForms.forEach((form) => {
        form.style.display = "none";
        });

        if(professorYearLevels[0] == '1'){
            form1.style.display = 'block'
        } else if(professorYearLevels[0] == '2'){
            form2.style.display = 'block'
        } else if(professorYearLevels[0] == '3'){
            form3.style.display = 'block'
        } else {
            form4.style.display = 'block'
        }


        var YearLevels = [];
        YearLevels.push("All")
        if (!professorYearLevels.includes("1")) {
            form1.style.display = "none"; // Hide form1 (Year 1)
        } else {
            YearLevels.push("1");
        }
        
        if (!professorYearLevels.includes("2")) {
            form2.style.display = "none"; // Hide form2 (Year 2)
        } else {
            YearLevels.push("2");
        }
        
        if (!professorYearLevels.includes("3")) {
            form3.style.display = "none"; // Hide form3 (Year 3)
        } else {
            YearLevels.push("3");
        }
        
        if (!professorYearLevels.includes("4")) {
            form4.style.display = "none"; // Hide form4 (Year 4)
        } else {
            YearLevels.push("4");
        }


        if (YearLevels.length > 0) {

            //set button dropdownm btn label to the first item in professorYearLevels
            var firstYEar = "";
            if(professorYearLevels[0] == '1'){
                firstYEar = '1st Year';
            } else if(professorYearLevels[0] == '2'){
                firstYEar = '2nd Year';
            } else if(professorYearLevels[0] == '3'){
                firstYEar = '3rd Year';
            } else if(professorYearLevels[0] == '4') {
                firstYEar = '4th Year';
            } else {
                firstYEar = 'All'
            }
            dropdownButton.innerHTML = `${firstYEar}<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>`;
          
            //setting up dropdown value if 1 then its 1st Year ....
            YearLevels.forEach((year) => {
              const dropdownItem = document.createElement("a");
              dropdownItem.href = "#";
              dropdownItem.className ="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white";
              if(year == '1'){
                dropdownItem.textContent = `${year}st Year`;
              } else if(year == '2'){
                dropdownItem.textContent = `${year}nd Year`;
              } else if(year == '3'){
                dropdownItem.textContent = `${year}rd Year`;
              } else if(year == '4'){
                dropdownItem.textContent = `${year}th Year`;
              } else {
                dropdownItem.textContent = `All`;
              }
          
              // Add click event to update button label
              dropdownItem.addEventListener("click", (event) => {
                event.preventDefault(); // Prevent default link behavior
                dropdownButton.innerHTML = `${dropdownItem.textContent} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>`;

                const allForms = [form1, form2, form3, form4];
                allForms.forEach((form) => {
                    form.style.display = "none";
                });
            
                // Show the selected form
                if (dropdownItem.textContent === "1st Year") {
                    form1.style.display = "block";
                } else if (dropdownItem.textContent === "2nd Year") {
                    form2.style.display = "block";

                } else if (dropdownItem.textContent === "3rd Year") {
                    form3.style.display = "block";

                } else if (dropdownItem.textContent === "4th Year") {
                    form4.style.display = "block";
                } else {
                    allForms.forEach((form) => {
                        form.style.display = "block";
                        if(allForms[0]){
                            form.classList.add('mt-8')
                        }
                    });    
                }

                dropdownMenu.classList.add("hidden");
              });
          
              // Add item to dropdown menu
              dropdownMenu.appendChild(dropdownItem);
            });
          } else {
            console.error("YearLevels is not defined or empty.");
          }
    }

    function SubjectDropdown(){
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        const button = document.getElementById('mysubjectsBtn');
     
    
        if (typeof firstSubject !== 'undefined' && firstSubject.subjectname && firstSubject.code) {
            button.textContent = `${firstSubject.subjectname} (${firstSubject.code})`;
        } else {
            button.textContent = "Subjects";
        }
    
        // Attach click event to each dropdown item
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const subjectName = item.getAttribute('data-subjectname');
                const code = item.getAttribute('data-code');
                
                button.innerHTML = `${subjectName} (${code}) <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>`;
    
                fetch(`/grades/${code}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        });
    }
});
