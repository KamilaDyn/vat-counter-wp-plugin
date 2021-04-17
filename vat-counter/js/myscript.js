(function () {
  const productName = document.querySelector('#prod_title'),
      netPrice = document.querySelector('#net_price'),
      vatRate = document.querySelector('#vat_rate'),
      form = document.querySelector('#form');

  const vatAmound = (price, rate) => {
      let totalAmound = price + (price * (rate / 100));

      return totalAmound.toFixed(2)
  }

  const vatPrice = (price, rate) => {
      let vatPrice = price * rate / 100;
      return vatPrice.toFixed(2);
  }

  let output = "";
  form.addEventListener('submit', (e) => {
      e.preventDefault();
      //     validacja ceny i nazwy produktu
      if (productName.value.length < 3) {
          alert('wpisz nazwę produktu, więcej niż 3 litery');
          return false;
      }
      if (parseFloat(netPrice.value) <= 0) {
          alert('kwota większa niż 0');
          return false;
      }
      let finalAmound = vatAmound(Number(netPrice.value), Number(vatRate.value))
      let finalVatPrice = vatPrice(Number(netPrice.value), Number(vatRate.value));
      output = `Cena produktu <span> ${productName.value}</span>, wynosi: <span name="final_amound">${finalAmound}</span> zł brutto, kwota podatku to <span id="final_vamound" name="final_vat"> ${finalVatPrice}</span> zł.`;
      const divOutput = document.getElementById('output');
      divOutput.innerHTML = output;
      // const data = new FormData();
      const data = {
          action: 'set_form',
          prod_title: productName.value,
          net_price: netPrice.value,
          final_vat: finalVatPrice,
          final_amound: finalAmound,
          vat_rate: `${vatRate.value}%`
      }

      //   data.append( 'nonce', my-plugin-script.nonce );
      fetch(`${vatData.root_url}/wp-json/vat-counter/v1/manageVat`, {
              method: 'POST',
              body: JSON.stringify(data),
              headers: {
                  "Content-type": "application/json; charset=UTF-8"
              }
          })
          .then(response => {
              console.log('Success:', response);
          })
          .catch((error) => {
              console.error('Error:', error);
          });
      form.reset();
  })

  function inputNumber(el) {
      let min = el.getAttribute('min') || 0;
      let max = el.getAttribute('max') || false;
      let price = document.querySelector('#net_price')
      let minValue = price.value;
      const elements = {};
      elements.dec = el.previousSibling;
      elements.inc = el.nextSibling;
      elements.dec.addEventListener('click', decrement);
      elements.inc.addEventListener('click', increment);
      let elem = document.querySelector(`[name="net_price"]`);

      function decrement(e) {
          if (elements.dec.classList.contains('isDisabled')) {
              e.preventDefault();
          } else {
              elem.value--;
              if (!min || minValue >= min) {
                  el.value = elem.value.toFixed(2);
              }
              if (elem.value <= 0 || false) {
                  elements.dec.classList.add('isDisabled');
              }
          }
      }

      function increment() {
          elem.value++;
          if (!max || minValue <= max) {
              el.value = parseFloat(elem.value++).toFixed(2);
          }
          if (elem.value > 0) {
              elements.dec.classList.remove('isDisabled');
          }
      }

  }

  inputNumber(document.querySelector('.input-number'));

})();