import $ from 'jquery';
import './bootstrap';
import feather from 'feather-icons';
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/silver/theme';

import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';
import 'flowbite';

window.$ = $;
window.feather = feather;

$(document).ready(function() {
    feather.replace({ 'stroke-width': 1 });

		tinymce.init({
			selector: 'textarea', // Select all textarea tags
			plugins: 'link lists',
			toolbar: 'undo redo | bold italic | bullist numlist | link',
			menubar: false,
			height: 300, // Set height
			branding: false, // Hide branding
			base_url: '/tinymce', // Path to TinyMCE assets
			suffix: '.min', // Ensure minified files are used
			//skin: 'oxide-dark', // Use the dark theme
			content_css: 'light', // Use the dark mode content CSS
		});
		
	
});


// Use Choices.js for dropdowns
const populateChoices = (selector, options, multiple=false, placeholder=null) => {
    console.log(options);
    /*let element = document.getElementById(selector);
    options.forEach(option => {
        let opt = document.createElement('option');
        opt.value = option.value;
        opt.textContent = option.label;
                element.appendChild(opt);
    });*/
    let obj = new Choices(`#${selector}`, {
        searchEnabled: true,
        itemSelectText: '',
        removeItemButton: multiple, // Allow multiple for Assignees & Distribution, single for Received From
        placeholderValue: placeholder ? placeholder : `Select ${selector.replace('-', ' ')}`,
    });
    obj.setChoices(options);
};



window.populateChoices = populateChoices;




