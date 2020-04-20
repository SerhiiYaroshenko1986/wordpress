require("jquery");
jQuery(document).ready(function($) {
  console.log("hello world");
  $(".categories-link").on("click", function(e) {
    e.preventDefault();
    let id = e.target.id;
    if (id === "0") {
      id = "";
    }
    let ajaxurl = "/wp-admin/admin-ajax.php";
    console.log(id, ajaxurl);
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
    console.log(nameOfClass, id);

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
    console.log(nameOfClass, id);
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

    jQuery.post(
      url,
      {
        // apiKey: "6416ecb9be5197c1d554d97514c7ccd3",
        modelName: "Address",
        calledMethod: "searchSettlements",
        methodProperties: {
          CityName: cityName,
          Limit: 5,
        },
      },

      function(output) {
        console.log(output);
        // $(".my-products").html(output);
      }
    );
  });

  // delivery (nova poshta api)
  $("#city").on("keypress", function() {
    clearTimeout($.data(this, "timer"));
    let wait = setTimeout(getDeliveryAddress, 500);
    $(this).data("timer", wait);
  });

  function getDeliveryAddress() {
    let cityName = $("#city").val();
    console.log(cityName);
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
          console.log(responde);
          $("#result").empty();
          $(".countDelivery").css("display", "none");
          $("#department")
            .empty()
            .css("display", "none");
          let data = responde.data;

          for (let i = 0; i < data.length; i++) {
            $("#result").append(
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
        console.log(value);
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
              console.log(responde.success);
              if (responde.success) {
                $("#department").empty();
                let data = responde.data;
                var CityRecipient = responde.data[0].CityRef;
                console.log(responde);
                console.log(CityRecipient, "city");
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
                  console.log(output);
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
                      console.log(responde);
                      let cost = responde.data[0].Cost;
                      $(".countDelivery").css("display", "block");
                      $("#deliveryCost").html(cost);
                      console.log(cost);
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
        .append("<p>Будь ласка введіть назву міста</p>");
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
  console.log("you press me!");
  e.preventDefault();
  // regEx for email
  let regEmail = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
  let emailFormat = regEmail.test($("#email").val());
  if (!emailFormat) {
    $("#emailError").css("display", "block");
  }
  // regEx for name (first and second)
  let regName = /([a-zA-Z]){3}$|([а-яА-ЯЁё]){3}$/i;
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
    $("#result").text("Заповніть будь ласка поле доставки");
  }
  if (
    !(
      firstNameFormat &&
      secondNameFormat &&
      phoneFormat &&
      emailFormat &&
      $("#city").val() == ""
    )
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
          $(".checkoutSuccsess").show();
        }
      }
    );
  } else {
    $("$result").html("Заповніть будь ласка усі поля");
  }
});

// Single page choose color
$(".productColor").on("click", function(e) {
  $(".productColor").removeClass("activeColor");
  $(".firstLoadError").css("display", "none");
  $(this).addClass("activeColor");
  let productId = $(this).attr("data-productid");
  let color = jQuery.trim(e.target.id);
  let size = jQuery.trim($(".activeSize")[0].id);
  console.log(color, productId);
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
      console.log(res);
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
        $(".quantityToOrderContainer").css("display", "block");
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
  $(".productSize").removeClass("activeSize");
  $(".firstLoadError").css("display", "none");
  $(this).addClass("activeSize");
  let color = jQuery.trim($(".activeColor")[0].id);
  let productId = $(this).attr("data-productid");
  let size = jQuery.trim(e.target.id);
  console.log(size, productId, color);
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
      console.log(res.quantityInStock);
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
        $(".quantityToOrderContainer").css("display", "block");
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
    $(this)
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
    if (
      $(this)
        .next()
        .val() > 1
    )
      $(this)
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
  console.log(color, productId, size, quantity);
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
      $(".lds-ring").css("display", "block");
    },
    success: function(data) {
      console.log(data);
    },
    error: function(xhr) {
      var errorMessage = xhr.status + ": " + xhr.statusText;
      console.log("Error - " + errorMessage);
    },
    complete: function() {
      $(".lds-ring").hide();
      $(".add-success--tick").show();
    },
  });
});
