$(document).ready(function () {
    webPageObject = new WebPage();


    function payWithPaystack(email , amount , name , originalAmount){
        var handler = PaystackPop.setup({
            key: 'pk_test_6e24123adb39a373e1fb9f978dc287e5a7e626c3',
            email: email,
            amount: amount,
            ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
            metadata: {
                custom_fields: [
                    {
                        display_name: name,
                        variable_name: "email_address",
                        value: email
                    }
                ]
            },
            callback: function(response){

                data = {"userID" : webPageObject.userDetails.user_id , "amount" : originalAmount , "referenceCode" : response.reference};
                data = JSON.stringify(data);

                $.post(webPageObject.defaults.files.fundAccountFile , {data: data}).done(function (data) {

                    if(data[webPageObject.defaults.jsonSuccessText] == "1"){

                        window.location.href = "/";
                    }

                    else {

                        window.location.href = "/";
                    }



                });

            },
            onClose: function(){
                window.console.log('window closed');
            }
        });
        handler.openIframe();
    }


if(webPageObject.isLoggedInUser){


    fundAccountAmountOptions = $('#fund-account-amount-options');
    fundAccountActionButton = $('#fund-account-action-button');



    fundAccountActionButton.on('click' , function (e) {
        webPageObject.defaults.preventFormSubmission(e);
        originalAmountInNaira = Number($('#'+fundAccountAmountOptions.attr('id') + " option:selected").attr("value"));
        fundAmount = originalAmountInNaira * 100;
        fundAmount = fundAmount + ((1.5/100) * fundAmount);


        payWithPaystack(webPageObject.userDetails.email , fundAmount , webPageObject.userDetails.fullname , originalAmountInNaira);

    });


}







});