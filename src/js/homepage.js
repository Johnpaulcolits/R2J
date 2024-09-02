
// This function is for header of the view pages
function headerFunction(){

       // Get all the navigation links
   const navLinks = document.querySelectorAll('.nav-link');

   // Function to highlight the active link
   function highlightActiveLink() {
       const currentPath = window.location.pathname;
       navLinks.forEach(link => {
           // Remove underline from all links
           link.classList.remove('underline', 'underline-offset-8', 'decoration-white');
           // Add underline to the link that matches the current path
           if (link.getAttribute('href') === currentPath) {
               link.classList.add('underline', 'underline-offset-8', 'decoration-white');
           }
       });
   }
   

   // Call the function to highlight the active link on page load
   highlightActiveLink();
   
   // Add event listener to each link
   navLinks.forEach(link => {
       link.addEventListener('click', function() {
           // Store the clicked link's href in localStorage
           localStorage.setItem('activeLink', this.getAttribute('href'));
       });
   });
   

   // On page load, apply the active link style
   document.addEventListener('DOMContentLoaded', () => {
       const activeLink = localStorage.getItem('activeLink');
       if (activeLink) {
           navLinks.forEach(link => {
               if (link.getAttribute('href') === activeLink) {
                   link.classList.add('underline', 'underline-offset-8', 'decoration-white');
               }
           });
       }
   });
}
headerFunction();