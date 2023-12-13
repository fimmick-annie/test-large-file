const utils = {
    //表單驗證
    validate: {
        validateEmpty: function(str) {
            return str.trim().length !== 0;
        },
        validateEN: function(str) {
            const re = /^[\d|a-zA-Z]+$/;
            return re.test(str.trim());
        },
        validateNumber: function(str) {
            const re = /^[\d|0-9]+$/;
            return re.test(str.trim());
        },
        validateENandNumber: function(str) {
            const re = /^[\d|a-zA-Z0-9]+$/;
            return re.test(str.trim());
        },
        validatePhoneHK: function(phone) {
            const re = /^[4-9]/;
            return re.test(phone) && !isNaN(phone) && phone.length === 8;
        },
        validatePhoneMacao: function(phone) {
            const re = /^[6]/;
            return re.test(phone) && !isNaN(phone) && phone.length === 8;
        },
        validatePhoneCN: function(phone) {
            const re = /^[1]/;
            return re.test(phone) && !isNaN(phone) && phone.length === 11;
        },
        validateEmail: function(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },
        error_checker: function(targetForm, arr) {
            const errormsg = $('.errormsg');
            errormsg.text('');
            $.each(arr, function() {
                targetForm.find('#' + this.targetId).siblings('.errormsg').text(this.errorMsg);
            });
        }
    },
    data: {
        getFormData: function($form) {
            const unindexed_array = $form.serializeArray();
            let indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        },
        getUniData: function(arr) {
            return [...(new Set(arr))];
        }
    }
}