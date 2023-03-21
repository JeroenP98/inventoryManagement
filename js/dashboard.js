
function showSection(sectionId, navLink) {
  // Get all the content sections
  var sections = document.querySelectorAll('.cardBodyJs > div');
  
  // Loop through each section and hide/show them as appropriate
  for (var i = 0; i < sections.length; i++) {
    if (sections[i].id === sectionId) {
      // Show the clicked section
      sections[i].classList.remove('d-none');
    } else {
      // Hide all other sections
      sections[i].classList.add('d-none');
    }
  }
  // Get all the nav links
  var navLinks = document.querySelectorAll('.nav-link');
  
  // Loop through each nav link and remove the active class
  for (var i = 0; i < navLinks.length; i++) {
    navLinks[i].classList.remove('active');
  }
  
  // Add the active class to the clicked nav link
  navLink.classList.add('active');
  
}




         