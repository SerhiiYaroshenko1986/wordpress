require("jquery");
import "slick-carousel";

jQuery(document).ready(function($) {
  $(".categories-link, .categories-link-all").on("click", function(e) {
    e.preventDefault();
    $(".categories-link, .categories-link-all").removeClass("activeCategory");
    $(this).addClass("activeCategory");
    $(".pa_size").removeClass("activeAttributes");
    $(".pa_color").removeClass("activeAttributes");
    let id = e.target.id;
    if (id === "0") {
      id = "";
    }
    let ajaxurl = "/wp-admin/admin-ajax.php";
    jQuery.post(
      ajaxurl,
      {
        action: "fetch_posts",
        fetch: id,
      },
      function(output) {
        $(".my-products").html(output);
      }
    );
  });

  // ajax request for color attribute sorting
  $(".pa_color").on("click", function(e) {
    e.preventDefault();

    let nameOfClass = this.className;
    let id = e.target.id;
    $(".categories-link, .categories-link-all").removeClass("activeCategory");
    $(".pa_size").removeClass("activeAttributes");
    $(".pa_color").removeClass("activeAttributes");
    $(this).addClass("activeAttributes");
    let ajaxurl = "/wp-admin/admin-ajax.php";

    jQuery.post(
      ajaxurl,
      {
        action: "fetch_attributes_color",
        id: id,
        className: nameOfClass,
      },
      function(output) {
        $(".my-products").html(output);
      }
    );
  });

  // ajax request for size attribute sorting
  $(".pa_size").on("click", function(e) {
    e.preventDefault();

    let nameOfClass = this.className;
    let id = e.target.id;
    $(".pa_color").removeClass("activeAttributes");
    $(".pa_size").removeClass("activeAttributes");
    $(".categories-link, .categories-link-all").removeClass("activeCategory");
    $(this).addClass("activeAttributes");
    let ajaxurl = "/wp-admin/admin-ajax.php";

    jQuery.post(
      ajaxurl,
      {
        action: "fetch_attributes_size",
        id: id,
        className: nameOfClass,
      },
      function(output) {
        $(".my-products").html(output);
      }
    );
  });
  $("#city").on("change", function(e) {
    e.preventDefault();
    let cityName = $("#city").val();

    let url = "https://api.novaposhta.ua/v2.0/json/";

    jQuery.post(url, {
      // apiKey: "6416ecb9be5197c1d554d97514c7ccd3",
      modelName: "Address",
      calledMethod: "searchSettlements",
      methodProperties: {
        CityName: cityName,
        Limit: 5,
      },
    });
  });

  // delivery (nova poshta api)
  $("#city").on("keypress", function() {
    clearTimeout($.data(this, "timer"));
    let wait = setTimeout(getDeliveryAddress, 500);
    $(this).data("timer", wait);
  });

  function getDeliveryAddress() {
    let cityName = $("#city").val();
    if (!(cityName == "")) {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "https://api.novaposhta.ua/v2.0/json/",
        data: JSON.stringify({
          modelName: "Address",
          calledMethod: "getCities",
          methodProperties: {
            FindByString: cityName,
          },
          apiKey: "6416ecb9be5197c1d554d97514c7ccd3",
        }),
        headers: {
          "Content-Type": "application/json",
        },
        xhrFields: {
          withCredentials: false,
        },
        success: function(responde) {
          $("#result").empty();
          $(".countDelivery").css("display", "none");
          $("#department")
            .empty()
            .css("display", "none");
          let data = responde.data;

          for (let i = 0; i < data.length; i++) {
            $("#result")
              .css("color", "#212529")
              .append(
                "<p class='cities' id=" +
                  data[i].Description +
                  ">" +
                  data[i].Description +
                  "</p>"
              );
          }
        },
      });
      $("#result").on("click", ".cities", function(event) {
        let value = event.target.id;
        $("#city").val(value);
        $("#result").empty();
        if (!(value == "")) {
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "https://api.novaposhta.ua/v2.0/json/",
            data: JSON.stringify({
              modelName: "AddressGeneral",
              calledMethod: "getWarehouses",
              methodProperties: {
                CityName: value,
              },
              apiKey: "6416ecb9be5197c1d554d97514c7ccd3",
            }),
            headers: {
              "Content-Type": "application/json",
            },
            xhrFields: {
              withCredentials: false,
            },
            success: function(responde) {
              if (responde.success) {
                $("#department").empty();
                let data = responde.data;
                var CityRecipient = responde.data[0].CityRef;
                for (let i = 0; i < data.length; i++) {
                  $("#department")
                    .append(
                      "<option class='cities' id=" +
                        data[i].Description +
                        ">" +
                        data[i].Description +
                        "</option>"
                    )
                    .css("display", "block");
                }
              }
              // ajax request for package attributes for delivery

              let ajaxurl = "/wp-admin/admin-ajax.php";
              jQuery.post(
                ajaxurl,
                {
                  action: "getDataDelivery",
                },
                function(output) {
                  let length = parseInt(+output.length);
                  let width = parseInt(+output.width);
                  let height = parseInt(+output.height);
                  let price = parseInt(+output.price);
                  let weight = parseInt(+output.weight);
                  let sizeWeight = (height * width * length) / 4000;
                  if (weight < sizeWeight) {
                    weight = sizeWeight;
                  }
                  // request to nova poshta api to get delivery cost
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "https://api.novaposhta.ua/v2.0/json/",
                    data: JSON.stringify({
                      modelName: "InternetDocument",
                      calledMethod: "getDocumentPrice",
                      methodProperties: {
                        CitySender: "db5c8904-391c-11dd-90d9-001a92567626",
                        CityRecipient: CityRecipient,
                        Weight: weight,
                        ServiceType: "DoorsDoors",
                        Cost: price,
                        CargoType: "Cargo",
                        SeatsAmount: "1",
                      },
                      apiKey: "6416ecb9be5197c1d554d97514c7ccd3",
                    }),
                    headers: {
                      "Content-Type": "application/json",
                    },
                    xhrFields: {
                      withCredentials: false,
                    },
                    success: function(responde) {
                      let cost = responde.data[0].Cost;
                      $(".countDelivery").css("display", "block");
                      $("#deliveryCost").html(cost);
                    },
                  });
                },
                "json"
              );
            },
          });
        }
      });
    } else {
      $("#result")
        .empty()
        .append("<p>Будь ласка введіть назву міста</p>")
        .css("color", "red");
    }
  }
});

$("#getPayment").on("click", function() {
  $(".payment").css("display", "block");
});

// create order
$("#checkout-button").on("click", function(e) {
  $("#emailError").css("display", "none");
  $("#firstNameError").css("display", "none");
  $("#secondNameError").css("display", "none");
  $("#phoneError").css("display", "none");
  e.preventDefault();
  // regEx for email
  let regEmail = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
  let emailFormat = regEmail.test($("#email").val());
  if (!emailFormat) {
    $("#emailError").css("display", "block");
  }
  // regEx for name (first and second)
  let regName = /^[аАбБвВгГґҐдДіІїЇеЕєЄжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩьЬюЮяЯ]{3,}/i;
  let firstNameFormat = regName.test($("#firstName").val());
  if (!firstNameFormat) {
    $("#firstNameError").css("display", "block");
  }
  let secondNameFormat = regName.test($("#secondName").val());
  if (!secondNameFormat) {
    $("#secondNameError").css("display", "block");
  }
  // regEx for phone number
  let regPhone = /^([+]38)?((3[\d]{2})([ ,\-,/]){0,1}([\d, ]{6,9}))|(((0[\d]{1,4}))([ ,\-,/]){0,1}([\d, ]{5,10}))$/;
  let phoneFormat = regPhone.test($("#phone").val());
  if (!phoneFormat) {
    $("#phoneError").css("display", "block");
  }
  if ($("#city").val() == "") {
    $("#result")
      .text("Заповніть будь ласка поле доставки")
      .css("color", "red");
  }
  if (
    firstNameFormat &&
    secondNameFormat &&
    phoneFormat &&
    emailFormat &&
    $("#city").val() != ""
  ) {
    let url = "/wp-admin/admin-ajax.php";
    let email = $("#email").val();
    let phone = $("#phone").val();
    let secondName = $("#secondName").val();
    let firstName = $("#firstName").val();
    let city = $("#city").val();
    let department = $("#department").val();
    jQuery.post(
      url,
      {
        action: "deliveryAttributes",
        firstName: firstName,
        secondName: secondName,
        email: email,
        phone: phone,
        city: city,
        department: department,
      },

      function(responde) {
        if (responde == "succsess") {
          $(".checkout").hide();
          $(".checkoutSuccsess").css("display", "flex");
        }
      }
    );
  } else {
    $("$result").html("Заповніть будь ласка усі поля");
  }
});

// Single page choose color
$(".productColor").on("click", function(e) {
  $(".quantityInStockContainerFirstLoad").hide();
  $(".quantityToOrderContainerFirstLoad").hide();
  $(".productColor").removeClass("activeColor");
  $(".firstLoadError").css("display", "none");
  $(this).addClass("activeColor");
  let productId = $(this).attr("data-productid");
  let color = jQuery.trim(e.target.id);
  let size = jQuery.trim($(".activeSize")[0].id);
  let url = "/wp-admin/admin-ajax.php";
  let data = {
    action: "getVariationImage",
    color: color,
    productId: productId,
    size: size,
  };
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    dataType: "text",
    success: function(data) {
      let res = jQuery.parseJSON(data);
      $("#productImage").attr("src", res.url);
      $(".item-price")
        .html(res.price)
        .append(" ₴");
      if (res.quantityInStock == "") {
        $(".quantityInStockContainerError").css("display", "block")[0];
        $(".quantityInStockContainer").css("display", "none");
        $(".quantityToOrderContainer").css("display", "none");
        $("#addToCartSinglePage")
          .prop("disabled", true)
          .css("cursor", "not-allowed");
      } else {
        $(".quantityInStockContainerError").css("display", "none");
        $(".addToCartSinglePage-btnText").show();
        $(".addToCartSuccsess").hide();
        $(".quantityInStock")
          .html(res.quantityInStock)
          .attr("id", res.quantityInStock);
        $("#quantityToOrder")
          .attr({
            max: res.quantityInStock,
            value: res.quantityInStock,
          })
          .val("1");
        $(".quantityInStockContainer").css("display", "block");
        $(".quantityToOrderContainer").css("display", "flex");
        $("#addToCartSinglePage")
          .prop("disabled", false)
          .css("cursor", "pointer");
        $(".sub")
          .prop("disabled", false)
          .css("cursor", "pointer");
        $(".add")
          .prop("disabled", false)
          .css("cursor", "pointer");
      }
    },
    error: function(xhr) {
      var errorMessage = xhr.status + ": " + xhr.statusText;
      console.log("Error - " + errorMessage);
    },
  });
});

// Single page choose size
$(".productSize").on("click", function(e) {
  $(".quantityInStockContainerFirstLoad").hide();
  $(".quantityToOrderContainerFirstLoad").hide();
  $(".productSize").removeClass("activeSize");
  $(".firstLoadError").css("display", "none");
  $(this).addClass("activeSize");
  let color = jQuery.trim($(".activeColor")[0].id);
  let productId = $(this).attr("data-productid");
  let size = jQuery.trim(e.target.id);
  let url = "/wp-admin/admin-ajax.php";
  let data = {
    action: "getVariationSizeQuantity",
    color: color,
    size: size,
    productId: productId,
  };
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    dataType: "text",
    success: function(data) {
      let res = jQuery.parseJSON(data);
      $(".item-price")
        .html(res.price)
        .append(" ₴");

      if (res.quantityInStock == "") {
        $(".quantityInStockContainerError").css("display", "block")[0];
        $(".quantityToOrderContainer").css("display", "none");
        $("#addToCartSinglePage")
          .prop("disabled", true)
          .css("cursor", "not-allowed");
        $(".quantityInStockContainer").css("display", "none");
      } else {
        $(".quantityInStockContainerError").css("display", "none");
        $(".addToCartSinglePage-btnText").show();
        $(".addToCartSuccsess").hide();
        $(".quantityInStock")
          .html(res.quantityInStock)
          .attr("id", res.quantityInStock);
        $("#quantityToOrder")
          .attr({
            max: res.quantityInStock,
            value: res.quantityInStock,
          })
          .val("1");
        $(".quantityInStockContainer").css("display", "block");
        $(".quantityToOrderContainer").css("display", "flex");
        $("#addToCartSinglePage")
          .prop("disabled", false)
          .css("cursor", "pointer");
        $(".sub")
          .prop("disabled", false)
          .css("cursor", "pointer");
        $(".add")
          .prop("disabled", false)
          .css("cursor", "pointer");
      }
    },
    error: function(xhr) {
      var errorMessage = xhr.status + ": " + xhr.statusText;
      console.log("Error - " + errorMessage);
    },
  });
});
// product Quantity
$(".add").click(function() {
  let maxquantity = +$(".quantityInStock")[0].id;
  $(".sub")
    .prop("disabled", false)
    .css("cursor", "pointer");
  if (
    $(this)
      .prev()
      .val() == maxquantity
  ) {
    $(".add")
      .prop("disabled", true)
      .css("cursor", "not-allowed");
  }
  if (
    $(this)
      .prev()
      .val() < maxquantity
  ) {
    +$(this)
      .prev()
      .val(
        +$(this)
          .prev()
          .val() + 1
      );
  }
});
$(".sub").click(function() {
  $(".add")
    .prop("disabled", false)
    .css("cursor", "pointer");
  if (
    $(this)
      .next()
      .val() == 1
  ) {
    $(".sub")
      .prop("disabled", true)
      .css("cursor", "not-allowed");
  }
  if (
    $(this)
      .next()
      .val() > 1
  ) {
    +$(this)
      .next()
      .val(
        +$(this)
          .next()
          .val() - 1
      );
  }
});

// Add to cart from single product page
$("#addToCartSinglePage").on("click", function() {
  let color = jQuery.trim($(".activeColor")[0].id);
  let productId = $(".activeColor").attr("data-productid");
  let size = jQuery.trim($(".activeSize")[0].id);
  let quantity = $("#quantityToOrder").val();
  let url = "/wp-admin/admin-ajax.php";
  let data = {
    action: "addToCartSinglePage",
    color: color,
    size: size,
    productId: productId,
    quantity: quantity,
  };
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    dataType: "text",
    beforeSend: function() {
      $(".addToCartSinglePage-btnText").css("display", "none");
      $(".lds-ring").css("display", "flex");
    },
    success: function(data) {
      if (data > 0) {
        $(".header-cart-count")
          .empty()
          .text(data);
      }
    },
    error: function(xhr) {
      var errorMessage = xhr.status + ": " + xhr.statusText;
      console.log("Error - " + errorMessage);
    },
    complete: function() {
      $(".lds-ring").hide();
      $(".addToCartSuccsess").show();
      function hideSuccsess() {
        $(".addToCartSuccsess").hide();
      }
      function showAddBtn() {
        $(".addToCartSinglePage-btnText").show();
      }
      setTimeout(hideSuccsess, 3000);
      setTimeout(showAddBtn, 5000);
    },
  });
});

// get slider on click
let counter = 0;
$(".release-item").on("click", function() {
  let windowSize = $(window).width();

  if (windowSize > 475 && counter == 0) {
    counter++;
    let productId = $(".activeColor").attr("data-productid");
    let url = "/wp-admin/admin-ajax.php";
    let data = {
      action: "singleProductGallery",
      productId: productId,
    };
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: "text",

      success: function(data) {
        let res = jQuery.parseJSON(data);
        for (let key in res) {
          $(".slider").append("<div>" + res[key] + "</div>");
        }
        $(".slider-wrap").show();
        // Slider
        $(".slider").slick({
          dots: true,
          infinite: false,
          speed: 300,
          slidesToShow: 1,
          slidesToScroll: 1,
          adaptiveHeight: true,
          centerMode: false,
          nextArrow: '<i class="fa fa-chevron-right"></i>',
          prevArrow: '<i class="fa fa-chevron-left"></i>',

          responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                dots: true,
              },
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              },
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              },
            },
          ],
        });
      },
      error: function(xhr) {
        var errorMessage = xhr.status + ": " + xhr.statusText;
        console.log("Error - " + errorMessage);
      },
    });
  } else if (counter > 0) {
    $(".slider-wrap").show();
  }
});
$(".slider-close-btn").on("click", function() {
  $(".slider-wrap").hide();
});

// add comments
$("#addCommentSubmit").on("click", function(e) {
  e.preventDefault();
  let date = new Date();
  date = date.toLocaleString();
  let postId = $(".activeColor").attr("data-productid");
  let comment_author = $("#name").val();
  let comment_content = $("#message").val();
  if (comment_author == "" || comment_content == "") {
    $(".errorCommentsForm").html("Поля повинні бути заповнені");
  } else {
    $(".errorCommentsForm").empty();
    let url = "/wp-admin/admin-ajax.php";
    let data = {
      action: "singleProductComments",
      postId: postId,
      comment_author: comment_author,
      comment_content: comment_content,
    };
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: "text",
      beforeSend: function() {},
      success: function(data) {
        if (data == "done") {
          $(".comments").prepend(
            "<div><span>" +
              comment_author +
              "</span><span>" +
              date +
              "</span></div><div>" +
              comment_content +
              "</div>"
          );
          $("#comments-form")[0].reset();
        }
      },
      error: function(xhr) {
        var errorMessage = xhr.status + ": " + xhr.statusText;
        console.log("Error - " + errorMessage);
      },
      complete: function() {},
    });
  }
});
// slider for probably buy section of single product page
$(".slider_probably_buy").slick({
  dots: true,
  arrows: false,
  infinite: false,
  speed: 300,
  slidesToShow: 2,
  slidesToScroll: 1,
  adaptiveHeight: false,
  centerMode: false,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: true,
      },
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
  ],
});

// search form
$("#search").on("click", function() {
  $("#search-text").val("");
  $(".search-field-container").css("display", "block");
  $(".overflowChange").css("overflow-y", "hidden");
});
$(".search-field-close-btn").on("click", function() {
  $(".search-field-container").css("display", "none");
  $(".overflowChange").css("overflow-y", "auto");
});
$("#search-text").on("keypress", function() {
  clearTimeout($.data(this, "timer"));
  let wait = setTimeout(getSearchItems, 500);
  $(this).data("timer", wait);
});
function getSearchItems() {
  $("#search-result").empty();
  $(".search-result-container").css({
    "overflow-y": "hidden",
  });
  let searchText = $("#search-text").val();
  let url = "/wp-admin/admin-ajax.php";
  let data = {
    action: "searchProductForm",
    searchText: searchText,
  };
  $.ajax({
    type: "POST",
    url: url,
    data: data,
    dataType: "text",
    beforeSend: function() {},
    success: function(data) {
      $("#search-result").prepend(data);
      $(".search-result-container").css({
        "overflow-y": "scroll",
        height: "100%",
      });
    },
    error: function(xhr) {
      var errorMessage = xhr.status + ": " + xhr.statusText;
      console.log("Error - " + errorMessage);
    },
    complete: function() {},
  });
}

// hamburger menu
let Closed = false;

$(".hamburger").click(function() {
  if (Closed == true) {
    $(".hamburger-container").css("top", "-10px");
    $(".header-nav-menu").animate(
      {
        width: "0",
      },
      "5000",
      function() {
        $(this).hide(500);
      }
    );
    $(this).removeClass("open");
    $(this).addClass("closed");
    $(".header-nav-menu").css("display", "block");
    Closed = false;
  } else {
    $(".hamburger-container").css("top", "-5px");
    $(".header-nav-menu").css("width", "40%");
    $(".header-nav-menu").show(500);
    $(this).removeClass("closed");
    $(this).addClass("open");
    $(".header-nav-menu").css("display", "block");
    Closed = true;
  }
});
$(window).on("resize", function() {
  let windowSize = +$(window).width();
  if (windowSize >= 992) {
    let displayValue = $(".header-nav-menu").css("display");
    if (displayValue == "none") {
      $(".header-nav-menu").css({
        display: "flex",
        width: "auto",
      });
    }
  } else if (windowSize > 768) {
    $(".categories-filter-wrap")
      .unbind()
      .css("display", "block");
  } else {
    $(".header-nav-menu").css("display", "none");
    $(".categories-filter-wrap").css("display", "none");
  }
});

// archive page
// show filters on mobile versions
$(".categories-filter-title").on("click", function() {
  let windowSize = +$(window).width();

  if (windowSize < 768) {
    $(".categories-filter-wrap").toggle(500);
  }
});

// subscribe form
$("#subscribe-button").on("click", function() {
  $(".subscribe-form-container ").show();
});
$(".close-btn-container ").on("click", function() {
  $(".subscribe-form-container ").hide();
});
