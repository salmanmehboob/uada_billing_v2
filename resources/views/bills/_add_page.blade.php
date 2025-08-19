<script>

    // Select all input elements with the class 'flatpickr-date'
    const flatpickrDates = document.querySelectorAll('.flatpickr-date');

    // Loop through each element and apply flatpickr
    flatpickrDates.forEach(input => {
        flatpickr(input, {
            monthSelectorType: 'static',
            static: true,
            dateFormat: "Y-m-d"  // You can also specify the date format
        });
    });

    //
    // document.getElementById('product-form').addEventListener('submit', function (e) {
    //     // Copy HTML from Quill editor to hidden input
    //     document.getElementById('description').value = quill.root.innerHTML;
    // });

    // // Repeater Handler for both Attributes and Variants
    // var formRepeaterAttributes = $('.form-repeater-attributes');
    //
    // if (formRepeaterAttributes.length) {
    //     var rowAttr = 2;
    //     var colAttr = 1;
    //
    //     formRepeaterAttributes.on('submit', function (e) {
    //         e.preventDefault();
    //     });
    //
    //     formRepeaterAttributes.repeater({
    //         show: function () {
    //             var fromControl = $(this).find('.form-control, .form-select');
    //             var formLabel = $(this).find('.form-label');
    //
    //             fromControl.each(function (i) {
    //                 var id = 'form-repeater-attr-' + rowAttr + '-' + colAttr;
    //                 $(fromControl[i]).attr('id', id);
    //                 $(formLabel[i]).attr('for', id);
    //                 colAttr++;
    //             });
    //
    //             rowAttr++;
    //             $(this).slideDown();
    //
    //             // Reinitialize select2
    //             $(this).find('.select2').each(function () {
    //                 $(this).select2({
    //                     placeholder: 'Placeholder text',
    //                     dropdownParent: $(this).closest('.position-relative').length
    //                         ? $(this).closest('.position-relative')
    //                         : $(this).parent()
    //                 }).next('.select2-container').css('width', '100%');
    //             });
    //         },
    //         hide: function (deleteElement) {
    //             if (confirm('Are you sure you want to delete this attribute?')) {
    //                 $(this).slideUp(deleteElement);
    //             }
    //         }
    //     });
    // }
    //
    // var formRepeaterVariants = $('.form-repeater-variants');
    //
    // if (formRepeaterVariants.length) {
    //     var rowVar = 2;
    //     var colVar = 1;
    //
    //     formRepeaterVariants.on('submit', function (e) {
    //         e.preventDefault();
    //     });
    //
    //     formRepeaterVariants.repeater({
    //         show: function () {
    //             var fromControl = $(this).find('.form-control, .form-select');
    //             var formLabel = $(this).find('.form-label');
    //
    //             fromControl.each(function (i) {
    //                 var id = 'form-repeater-var-' + rowVar + '-' + colVar;
    //                 $(fromControl[i]).attr('id', id);
    //                 $(formLabel[i]).attr('for', id);
    //                 colVar++;
    //             });
    //
    //             rowVar++;
    //             $(this).slideDown();
    //
    //             // Reinitialize select2
    //             $(this).find('.select2').each(function () {
    //                 $(this).select2({
    //                     placeholder: 'Placeholder text',
    //                     dropdownParent: $(this).closest('.position-relative').length
    //                         ? $(this).closest('.position-relative')
    //                         : $(this).parent()
    //                 }).next('.select2-container').css('width', '100%');
    //             });
    //         },
    //         hide: function (deleteElement) {
    //             if (confirm('Are you sure you want to delete this variant?')) {
    //                 $(this).slideUp(deleteElement);
    //             }
    //         }
    //     });
    // }
    //
    //
    //
    // const quill = new Quill('#ecommerce-category-description', {
    //     theme: 'snow',
    //     placeholder: 'Write product description here...',
    //     modules: {
    //         toolbar: [
    //             ['bold', 'italic', 'underline'],
    //             [{'list': 'ordered'}, {'list': 'bullet'}],
    //             ['link', 'image']
    //         ]
    //     }
    // });
    //
    //
    // Dropzone.autoDiscover = false;
    //
    // const myDropzone = new Dropzone("#dropzone-basic", {
    //     url: "/admin/product/products/temp-upload",
    //     paramName: "file", // Dropzone default
    //     maxFilesize: 2, // in MB
    //     acceptedFiles: "image/*",
    //     addRemoveLinks: true,
    //     headers: {
    //         'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    //     },
    //     success: function (file, response) {
    //         // Optionally store the uploaded filename in a hidden input
    //         let input = document.createElement('input');
    //         input.type = 'hidden';
    //         input.name = 'uploaded_images[]';
    //         input.value = response.filename;
    //         file.previewElement.appendChild(input);
    //     },
    //     error: function (file, response) {
    //         alert("Upload failed: " + response);
    //     }
    // });
    //
    //
    // // Basic Tags
    //
    // const tagifyBasicEl = document.querySelector('#ecommerce-product-tags');
    // const TagifyBasic = new Tagify(tagifyBasicEl);


    // Select2
    var select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: $this.parent(),
                placeholder: $this.data('placeholder') // for dynamic placeholder
            });
        });
    }
</script>
