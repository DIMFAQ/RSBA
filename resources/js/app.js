import './bootstrap';


// print area
window.printArea = function (elementId) {
    let printContents = document.getElementById(elementId).innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents; // Replace page content with print content
    window.print(); // Show print dialog
    document.body.innerHTML = originalContents; // Restore original content after printing

    location.reload(); // Reload to restore Livewire functionality
};