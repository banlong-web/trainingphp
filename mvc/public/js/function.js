jQuery(document).ready(function ($) {
    var page = 1,
        limit = 0,
        totalRecords = 0,
        offset = 0;
    var arrData = '';
    var dataFilter = [];
    fetchData();
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
                    var propertyData = response.success.propertyProduct;
                    var propertyType = response.success.property;
                    var pagination = response.success.pagination;
                    $('#table_products').html(render_product(arrData, propertyData, propertyType));
                    if(totalRecords > limit) {
                        $('#pagination').html(pagination);
                    }
                }
            }
        });
    }

    // Get product detail
    // Add product
    let productImg = '';
    let productGallery = [];
    let productName = '';
    var regexText =/^[a-zA-Z0-9][a-zA-Z0-9 ._-]+$/;
	var regexPrice = /^[0-9]{0,10}$/;
    $('.product-name').on('change', function() {
        productName = $('.product-name').val();
    });
    $('#product_img').on('change', function(e) {
        var form_img = new FormData();
        form_img.append('product_img', $(this).get(0).files[0]);
       
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
                    $('.product_img .file-img').html(img);
                    $('.product_img .message-error').append(response.error);
                }
            }
        });
    });
    $('#product_gallery').on('change', function() {
        productGallery = [];
        var form_gallery = new FormData();
        for (var i = 0; i < $(this).get(0).files.length; i++) {
            form_gallery.append('product_gallery[]', $(this).get(0).files[i]);
        }
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
    });
   
    $('#add-product').on('click', function(e) {
        e.preventDefault();
        $('.message-error').html('');
        var add_status = '';
        var skuProduct = $("input[name=product_sku]").val();
        var priceProduct = $("input[name=product_price]").val();
        var typeProperty = $("#type-property").val();
        var arrTypeProperty = typeProperty.split(',');
        let nameProperty = [];
        add_status = true;
        $.each(arrTypeProperty, (index, value) => {
            nameProperty.push(...$('#'+value).val());
        });
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
        if (productName !=='' && add_status == true) {
            var dataProduct = [
                {
                    'product_name': productName,
                    'product_sku' : skuProduct ? skuProduct : productName.replaceAll(' ', '-').toLocaleLowerCase(),
                    'product_price' : priceProduct,
                    'property_name' : nameProperty.join(','),
                    'product_img' : productImg,
                    'product_gallery': productGallery.join(',')
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
                        var propertyData = response.propertyProduct;
                        var propertyType = response.property;
                        $('#table_products').html(render_product(data, propertyData, propertyType));
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
                        var propertyData = response.propertyProduct;
                        var propertyType = response.property;
                        if(response.pagination !=='') {
                            var pagination = response.pagination;
                            if(totalRecords > limit) {
                                $('#pagination').html(pagination);
                            }
                        } else {
                            $('#pagination').html('');
                        }
                        $('#table_products').html(render_product(arrData, propertyData, propertyType));
                    } else {
                        alert('Not Found!');
                    }
                }

            },
            error: function(error) {
            }
        })
    }
    // Render HTML
    function render_product(data, propertyData, propertyType) {
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
                    "<td>"+data[i].sku+"</td>"+
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

// for (let index = 0; index < propertyType.length; index++) { 
//     html += "<td>";
//     var f = '';
//     for (let j = 0; j < propertyData.length; j++) {
//         if(propertyData[j].length > 0) {
//             let arrPopertyData = propertyData[j];
//             for (let temp = 0; temp < arrPopertyData.length; temp++) {
//                 var g = [];
//                 if(data[i].product_id == arrPopertyData[temp].product_id) {
//                     if(propertyType[index].property_type == arrPopertyData[temp].property_type) {
//                         namePropertyDisplay = arrPopertyData[temp].property_name;
//                         f += namePropertyDisplay+', ';
//                     }
//                 }
                
//             }
//         }
//     }
//     html += "<p class='name-property'>"+f.substring(0, f.length - 2)+"</p>";
//     html += "</td>";
// }