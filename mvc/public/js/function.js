jQuery(document).ready(function ($) {
    // Create variable base
    var page = 1,
        limit = 0,
        totalRecords = 0,
        offset = 0;
    var arrData = '';
    var dataFilter = [];

    // Show data by ajax
    fetchData();

    // Create Next Page
    $(document).on('click', '.next-page', function(e) {
        e.preventDefault();
        if(page * limit < totalRecords) {
            page++;
            offset = (offset + 4);
            if(dataFilter.length > 0) {
                filter_product(dataFilter);
            } else {
                fetchData();
            }
            
        }
    });

    // Create Previous Page
    $(document).on('click', '.prev-page', function(e) {
        e.preventDefault();
        if(page > 1) {
            page--;
            offset = (offset - 4);
            if(dataFilter.length > 0) {
                filter_product(dataFilter);
            } else {
                fetchData();
            }
        }
    });

    // Get img and upload img
    let productImg = '';
    let productGallery = [];
    var regexText =/[a-zA-Z0-9_-].+/;
	var regexPrice = /^[0-9]{0,10}$/;

    // Add products images
    $('#product_img').on('change', function() {
        var form_img = new FormData();
        form_img.append('product_img', $(this).get(0).files[0]);
        uploadImg(form_img);
    });
    $('#product_gallery').on('change', function() {
        productGallery = [];
        var form_gallery = new FormData();
        for (var i = 0; i < $(this).get(0).files.length; i++) {
            form_gallery.append('product_gallery[]', $(this).get(0).files[i]);
        }
        uploadGallery(form_gallery);
    });

    // Edit product products images
    $('#product_img_edit').on('change', function() {
        var form_img = new FormData();
        form_img.append('product_img', $(this).get(0).files[0]);
        editUploadImg(form_img);
    });
    $('#product_gallery_edit').on('change', function() {
        productGallery = [];
        var form_gallery = new FormData();
        for (var i = 0; i < $(this).get(0).files.length; i++) {
            form_gallery.append('product_gallery[]', $(this).get(0).files[i]);
        }
        editUploadGallery(form_gallery);
    });
   
    // Add product
    $('#add-product').on('click', function(e) {
        e.preventDefault();
        $('.message-error').html('');
        var add_status = true;
        // Get data need add
        var productName = $('.product-name').val();
        var skuProduct = $(".product-sku").val();
        var descriptionProduct = $(".product-description").val();
        var priceProduct = $(".product-price").val();
        var discountProduct = $(".product-discount").val();
        var typeProperty = $("#type-property").val();
        var arrTypeProperty = typeProperty.split(',');
        let nameProperty = [];
        $.each(arrTypeProperty, (index, value) => {
            nameProperty.push(...$('#'+value).val());
        });
        // Validate data
        if(productName == '') {
            $('.product-name').addClass('error');
            $('#product_name_form .message-error').html("Enter product name!");
            add_status = false;
        } else if (productName !== '' && regexText.test(productName) === false) {
            $('.product-name').addClass('error');
            $('#product_name_form .message-error').html("Product name does not contain special characters!");
            add_status = false;
        } else {
            $('.product-name').removeClass('error');
        }
        if (skuProduct !== '' && regexText.test(skuProduct) === false) { 
            $('.product-sku').addClass('error');
            $('.product_sku .message-error').html("SKU does not contain special characters!");
            add_status = false;
        } else {
            $('.product-sku').removeClass('error');
        }
        if (priceProduct !== '' && regexPrice.test(priceProduct) === false) { 
            $('.product-price').addClass('error');
            $('.product_price .message-error').html("Invalid product price!");
            add_status = false;
        } else {
            $('.product-price').removeClass('error');
        }
        // Request Data by ajax
        if (productName !=='' && add_status == true) {
            var dataProduct = [
                {
                    'product_name'      : productName,
                    'product_sku'       : skuProduct ? skuProduct : productName.replaceAll(' ', '-').toLocaleLowerCase(),
                    'description'       : descriptionProduct,
                    'product_price'     : priceProduct,
                    'discount'          : discountProduct,
                    'property_name'     : nameProperty.join(','),
                    'product_img'       : productImg,
                    'product_gallery'   : productGallery.join(',')
                }
            ];
            $.ajax({
                url: 'add-products/add',
                method: 'POST',
                dataType: 'json',
                data: {
                    data: dataProduct
                },
                success: function(response) {
                    if (response.success == false) {
                        alert('There is an error. Please check it again!');
                        $('.product-name').addClass('error');
                        $('#product_name_form .message-error').html("Product has been added!");
                    }
                    if (response.success == true) {
                        alert('Product added successfully!');
                    }
                }, 
                error: function() {

                }
            });
        }
    });

    // Edit product
    $('.edit-product').on('click', function (e) {
        e.preventDefault();
        var productName = $('.product-name').val();
        var productSKU = $(".product-sku").val();
        var descriptionProduct = $(".product-description").val();
        var productPrice = $(".product-price").val();
        var discountProduct = $(".product-discount").val();
        var typeProperty = $("#type-property").val();
        var arrTypeProperty = typeProperty.split(',');
        let nameProperty = [];
        var id = $('#product-id').val();
        $.each(arrTypeProperty, (index, value) => {
            nameProperty.push(...$('#'+value).val());
        });
        if(productName == '') {
            $('.product-name').addClass('error');
            $('.product_name_form .message-error').html("Enter product name!");
        } else if (productName !== '' && regexText.test(productName) === false) {
            $('.product-name').addClass('error');
            $('.product_name_form .message-error').html("Product name does not contain special characters!");
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
        if (discountProduct !== '' && regexPrice.test(discountProduct) === false) { 
            $('.product-discount').addClass('error');
            $('.product_discount_form .message-error').html("Invalid product price!");
        } else {
            $('.product-price').removeClass('error');
        }

        if(productImg === '') {
            productImg = $('.form-edit-img .product-render-img').data('img');
        }
        if(productGallery.length == 0) {
            $('.form-edit-gallery .gallery .product-render-img').each((i, ele) => {
                var dataGallery = $(ele).data('gallery');
                productGallery.push(dataGallery);
            })
        }
        if(productName !== '' && regexText.test(productName) === true) {
            var dataProduct = [
                {
                    'product_name'      : productName,
                    'product_sku'       : productSKU ? productSKU : productName.replaceAll(' ', '-').toLocaleLowerCase(),
                    'description'       : descriptionProduct,
                    'product_price'     : productPrice,
                    'discount'          : discountProduct,
                    'property_name'     : nameProperty.join(','),
                    'product_img'       : productImg ? productImg : '',
                    'product_gallery'   : productGallery.join(',')
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
                    if(response.success === true) {
                        alert('The product has updated successfully!');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    // Search product
    $('.submit-search').on('click', function(e) {
        e.preventDefault();
        var dataSearch = $('.search-product .search-value').val();
        $.ajax({
            url: 'home/search',
            dataType: 'json',
            method: 'GET',
            data: {
                s: dataSearch,
                limit: limit,
                pageNum: page,
                offset: offset
            },
            success: function(response) {
                if(response) {
                    var data = response.data;
                    if (data.length > 0) {
                        totalRecords = response.totalRecords;
                        limit = response.fetched;
                        offset = response.offset;
                        $('#table_products').html(render_product(data));
                        $('.list-products .container').removeClass('hidden');
                        $('.list-products .message-error').html('');
                    } else {
                        $('.list-products .container').addClass('hidden');
                        $('.list-products .message-error').html('Not Found');
                    }
                    if(response.pagination) {
                        var pagination = response.pagination;
                        if(pagination !== '') {
                            $('#pagination').html(pagination);
                        }
                    }
                    if(response.searchValue == '') {
                        location.reload(true);
                    }
                }
                
            }, 
            error: function() {

            }
        })
    });

    // Filter product
    $('.submit-filter').on('click', function(e) {
        e.preventDefault();
        var fieldAttr = $('#field_attr').val();
        var sort = $('#sorting').val();
        var typeProperty = $("#type-property").val();
        var arrTypeProperty = typeProperty.split(',');
        let nameProperty = [];
        $.each(arrTypeProperty, (index, value) => {
            if($('#'+value).val()) {
                nameProperty.push($('#'+value).val());
            }
        });
        var dateFrom = $('#from_date').val();
        var dateTo = $('#to_date').val();
        var filterData = [
            {
                'field_attr': fieldAttr,
                'sort': sort,
                'property_name': nameProperty.join(','),
                'date_from': dateFrom,
                'date_to': dateTo
            }
        ];
        if(fieldAttr !== '' || nameProperty.length > 0 || (dateFrom !=='' && dateTo !== '')) {
            dataFilter = filterData;
            filter_product(dataFilter);
        } else {
            dataFilter = [];
            alert('Please select an item filter!');
            fetchData();
        }
       
    });

    // Function filter products
    function filter_product(dataFilter) {
        $.ajax({
            url: 'home/filter',
            method: 'get',
            data: {
                data: dataFilter,
                limit: limit,
                pageNum: page,
                offset: offset
            },
            dataType: 'json',
            success: function(response) {
                if(response) {
                    totalRecords = response.totalRecords;
                    limit = response.fetched;
                    offset = response.offset;
                    if(response.data !== null) {
                        arrData = response.data;
                    } else {
                        arrData = '';
                    }
                    if (arrData.length > 0) {
                        totalRecords = response.totalRecords;
                        limit = response.fetched;
                        offset = response.offset;
                        if(response.pagination !=='') {
                            var pagination = response.pagination;
                            if(totalRecords > limit) {
                                $('#pagination').html(pagination);
                            }
                        } else {
                            $('#pagination').html('');
                        }
                        $('#table_products').html(render_product(arrData));
                    } else {
                        alert('Not Found!');
                    }
                }

            },
            error: function() {
            }
        })
    }

    // Function Render HTML
    function render_product(data) {
        var html = '';
        for (let i = 0; i < data.length; i++) {
            var date = new Date(data[i].create_date);
            var galleryImg = data[i].gallery.split(',');
            var displayGallery = '';
            for (let index = 0; index < galleryImg.length; index++) {
                displayGallery += '<img class="product-render-img" src="'+((galleryImg[index]) ? galleryImg[index] : '')+'">';
                
            }
            html += 
                "<tr>"+
                    "<td>"+((date.getDate() > 9) ? (date.getDate()) : ("0" + (date.getDate())))+"/"+((date.getMonth() > 9) ? (date.getMonth()) : ("0" + (date.getMonth())))+"/"+date.getFullYear()+"</td>"+
                    "<td><div class='name-product'>"+data[i].product_name+"</div></td>"+
                    "<td><div class='sku'>"+data[i].sku+"</div></td>"+
                    "<td>"+data[i].price+"</td>"+
                    "<td>"+data[i].discount+"</td>"+
                    "<td><div class='rate'>"+data[i].rate+"</div></td>"+
                    "<td>"+((data[i].featured_img) ? ('<img class="product-render-img" src="'+data[i].featured_img+'">') : '')+"</td>"+
                    "<td><div class='gallery'>"+displayGallery+"</div></td>"+
                    "<td><div class='brand-content'>"+data[i].brand+"</div></td>"+
                    "<td><div class='cate-content'>"+data[i].category+"</div></td>"+
                    "<td><div class='tag-content'>"+data[i].tag+"</div></td>"+
                    "<td>"+
                        "<div class='action-product'>"+
                            "<a class='go-edit' href='edit-product/?id="+data[i].product_id+"'><i class='bx bx-edit'></i></a>"+
                            "<a href='home/delete/"+data[i].product_id+"'><i class='bx bxs-trash'></i></a>"+
                        "</div>"+
                    "</td>"+
                "</tr>";
        }
        return html;
    }
    
    // Fetch data show home
    function fetchData() {
        $.ajax({
            url: 'home/fetchData',
            method: 'GET',
            data: {
                pageNum: page,
                limit: limit,
                offset: offset
            },
            cache: true,
            dataType: 'json',
            contentType: '',
            success: function(response) {
                if(response.success) {
                    offset = response.success.offset;
                    totalRecords = response.success.totalRecords;
                    limit = response.success.fetched;
                    arrData = response.success.product;
                    var pagination = response.success.pagination;
                    $('#table_products').html(render_product(arrData));
                    if(totalRecords > limit) {
                        $('#pagination').html(pagination);
                    }
                }
            }
        });
    }
    // Upload Image
    function uploadImg(form_img) {
        $.ajax({
            url: 'upload/uploadImg',
            method: 'POST',
            dataType: 'json',
            data: form_img,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response) {
                    img = "<img class='product-render-img' src='" + response.pathName + "' data-img='"+response.pathName+"'>";
                    productImg = response.pathName;
                    $('.product-img .file-img').html(img);
                    $('.product-img .message-error').append(response.error);
                }
            }
        });
    }
    // Upload gallery img
    function uploadGallery(form_gallery) {
        $.ajax({
            url: 'upload/uploadGallery',
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
                    $('.product_gallery .message-error').append(response.error);
                }
       
                $('.product_gallery .file-img').html(gallery);
            }
        });
    }

    function editUploadGallery(form_gallery) {
        $.ajax({
            url: '../upload/uploadGallery',
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
                    $('.form-edit-gallery .message-error').append(response.error);
                }
       
                $('.form-edit-gallery .file-img').html(gallery);
            }
        });
    }

    function editUploadImg(form_img) {
        $.ajax({
            url: '../upload/uploadImg',
            method: 'POST',
            dataType: 'json',
            data: form_img,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response) {
                    img = "<img class='product-render-img' src='" + response.pathName + "' data-img='"+response.pathName+"'>";
                    productImg = response.pathName;
                    $('.form-edit-img .file-img').html(img);
                    $('.form-edit-img .message-error').append(response.error);
                }
            }
        });
    }

    // Sync data
    $('.sync-villatheme').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'sync-villatheme/syncData',
            method: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('.list-products').append('<div class="overlay"><div>');
                $('.list-products').append('<div class="loading"><div>');
            },
            success: function(response) {
                if(response.add == true) {
                    alert('Sync new products successfully!');
                }
                if(response.update == true) {
                    alert('Update products successfully!');
                }
            },
            error: function() {

            },
            complete: function() {
                fetchData();
                $('.list-products .overlay').remove();
                $('.list-products .loading').remove();
            },
        })
    });
}, jQuery);