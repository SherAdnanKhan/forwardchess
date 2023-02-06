<template>
  <div id="paypal-button-container"></div>
</template>
<script>
export default {
  name: "Biyearly",
  props: {
    planId: {
      type: String,
      required: true,
    },
  },
  mounted: function () {
      document.getElementById("paypal-button-container").id =
      "paypal-button-container-" + this.planId;
    const script = document.createElement("script");
    script.src =
      "https://www.paypal.com/sdk/js?client-id=ARaSs19Uvz-p_uI4U7vYBlOsmVMFjKp814-c0TfCgnhlz5Y8v2FlYzWMgpbnl73ClOy6xxC3Z90QuCDA&vault=true&intent=subscription";
    script.addEventListener("load", this.setLoaded);
    document.body.appendChild(script);
  },

  methods: {
       setLoaded: function() {
      this.loaded = true;
      window.paypal
        .Buttons({
          createOrder: async (data, actions) => {
            return await fetch('/subscribe/paypal', {
              method: 'post',
              headers: {
                'Content-Type': 'application/json',
                "Accept": "application/json",
                "X-CSRF-Token": $('input[name="_token"]').val()
              },
              credentials: "same-origin",
              body: JSON.stringify({
                firstName: this.form.firstName,
                lastName: this.form.lastName
              }),
            }).then((res) => {
              return res.json();
            }).then((data) => {
              let token;
              for (let link of data.links) {
                if (link.rel === 'approval_url') {
                    token = link.href.match(/EC-\w+/)[0];
                }
              }
              return token;
            });
          },

          onApprove: async (data, actions) => {
               console.log(data);
            //   return await fetch('/update-payment', {
            //     method: 'post',
            //     headers: {
            //       'Content-Type': 'application/json',
            //       "Accept": "application/json",
            //       "X-CSRF-Token": $('input[name="_token"]').val()
            //     },
            //     credentials: "same-origin",
            //     body: JSON.stringify({
            //       paymentID: data.paymentID,
            //       payerID: data.payerID
            //     }),
            //   }).then((res) => {
            //     return res;
            //   }).then((data) => {
            //     if (this.paymentMethod === 'paypal') {
            //       ga('send', 'event', 'Checkout', 'checkout_button_paypal');
            //     } else {
            //       ga('send', 'event', 'Checkout', 'checkout_button_card');
            //     }
            //     window.location.href = data.url;
            //   });
          },

          onError: err => {
            console.log(err);
          }
        })
        .render(this.$refs.paypal);
    }
  },
};
</script>
