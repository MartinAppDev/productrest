var app = new Vue({
    el: '#app',
    data: {
        customAttributes: [],
        newCustomAttribute: {
            "attribute_code": null,
            "value": null
        },
        showProductAlert: false
    },
    methods: {
        addAttribute: function (e) {
            if (!this.newCustomAttribute["attribute_code"]) return;
            this.customAttributes.push(Object.assign({}, this.newCustomAttribute));
        },
        removeAttribute: function(index) {
            this.customAttributes.splice(index, 1);
        },
        submit: function(e) {
            const formData = new FormData(e.target);

            formData.append("customAttributes", JSON.stringify(this.customAttributes));

            const request = new XMLHttpRequest();
            request.open("POST", "index.php");
            request.onload = function () {
                app.showProductAlert = this.status === 200;
            };
            request.send(formData);

            this.formData = {};
        }
    }
  })