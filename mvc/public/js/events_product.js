jQuery(document).ready(function ($) {
    // Edit product
    var regexText =/^[a-zA-Z0-9][a-zA-Z0-9 ._-]+$/;
	var regexPrice = /^[0-9]{0,10}$/;
    var productName = $("input[name=product_name]").val();
    var productSKU = $("input[name=product_sku]").val();
    var productPrice = $("input[name=product_price]").val();
    var typeProperty = $("#type-property").val();
    var arrTypeProperty = typeProperty.split(',');
    let productGallery = [];
    let productImg = $('.file-img img').data('img');
    if ($("input[name=product_name]").on('change', function () {
        productName = $("input[name=product_name]").val();
    }));
    if ($("input[name=product_sku]").on('change', function () {
        productSKU = $("input[name=product_sku]").val();
    }));
    if ($("input[name=product_price]").on('change', function () {
        productPrice = $("input[name=product_price]").val();
    }));
    $('.product-gallery-edit .gallery .product-render-img').each((i, ele) => {
        var dataGallery = $(ele).data('gallery');
        productGallery.push(dataGallery);
    });
    if($('#product_img').on('change', function () {
        var form_img = new FormData();
        form_img.append('product_img', $(this).get(0).files[0]);

        $.ajax({
            url: '/upload/uploadImg',
            method: 'POST',
            dataType: 'json',
            data: form_img,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response) {
                    img = "<img class='product-render-img' src='" + response.pathName + "' data-img='"+response.pathName+"'>";
                    productImg = response.pathName;
                    $('.product-img-edit .file-img').html(img);
                    $('.product-img-edit .message-error').append(response.error);
                }
            }
        });
    }));
    if($('#product_gallery').on('change', function() {
        productGallery = [];
        var form_gallery = new FormData();
        for (var i = 0; i < $(this).get(0).files.length; i++) {
            form_gallery.append('product_gallery[]', $(this).get(0).files[i]);
        }
        $.ajax({
            url: '/upload/uploadGallery',
            method: 'POST',
            dataType: 'json',
            data: form_gallery,
            processData: false,
            contentType: false,
            success: function(response) {
                var gallery = '';
                if (response) {
                    gallery += '<div class="gallery">';
                    for (let index = 0; index < response.pathName.length; index++) {
                        gallery += "<img class='product-render-img' src='"+response.pathName[index]+"' data-img='"+response.pathName[index]+"'>";
                        productGallery.push(response.pathName[index]);
                    }
                    gallery += '</div>';
                    $('.product-gallery-edit .message-error').append(response.error);
                }
       
                $('.product-gallery-edit .file-img').html(gallery);
            }
        });
    }));
    $('.edit-product').on('click', function (e) {
        e.preventDefault();
        let nameProperty = [];
        var id = $('#product-id').val();
        $.each(arrTypeProperty, (index, value) => {
            nameProperty.push(...$('#'+value).val());
        });
        if(productName == '') {
            $('.product-name').addClass('error');
            $('#product_name_form .message-error').html("Enter product name!");
        } else if (productName !== '' && regexText.test(productName) === false) {
            $('.product-name').addClass('error');
            $('#product_name_form .message-error').html("Product name does not contain special characters!");
        } else {
            $('.product-name').removeClass('error');
        }
        if (productSKU !== '' && regexText.test(productSKU) === false) { 
            $('.product-sku').addClass('error');
            $('.product_sku_form .message-error').html("SKU does not contain special characters!");
        } else {
            $('.product-sku').removeClass('error');
        }
        if (productPrice !== '' && regexPrice.test(productPrice) === false) { 
            $('.product-price').addClass('error');
            $('.product_price_form .message-error').html("Invalid product price!");
        } else {
            $('.product-price').removeClass('error');
        }
        if(productName !== '' && regexText.test(productName) === true) {
            var dataProduct = [
                {
                    'product_name': productName,
                    'product_sku' : productSKU,
                    'product_price' : productPrice,
                    'property_name' : nameProperty.join(','),
                    'product_img' : productImg,
                    'product_gallery': productGallery.join(',')
                }
            ]
            $.ajax({
                url: 'edit',
                method: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    dataProduct: dataProduct
                },
                success: function (response) {
                    if (response.base_property.length > 0) {
                        var arrProperty = response.base_property;
                        for (let index = 0; index < arrProperty.length; index++) {
                            nameProperty.push(arrProperty[index].property_slug);
                        }
                    }
                    if(response.success === 'same') {
                        alert('The product has no updates!');
                    }
                    if(response.success == 'true') {
                        alert('The product has updated successfully!');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
     
    });
}, jQuery);