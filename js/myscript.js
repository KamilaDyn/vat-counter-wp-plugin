jQuery(document).ready(function ($) {
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
            if ( parseFloat(netPrice.value) == 0) {
                alert('kwota większa niż 0');
                return false;
            }

            let finalAmound = vatAmound(Number(netPrice.value), Number(vatRate.value))


            let finalVatPrice = vatPrice(Number(netPrice.value), Number(vatRate.value));

            output = `Cena produktu ${productName.value}, wynosi: <span name="final_amound">${finalAmound}</span> zł brutto, kwota podatku to <span id="final_vamound" name="final_vat"> ${finalVatPrice}</span> zł.`;
            const divOutput = document.getElementById('output');


            divOutput.innerHTML = output;
            $.ajax({
                beforeSend: (xhr) => {
                    xhr.setRequestHeader('X-WP-Nonce', cpm_object.nonce);
                },
                method: 'POST',
                url: cpm_object,
                data: {
                    action: 'set_form',
                    prod_title: productName.value,
                    net_price: netPrice.value,
                    final_vat: finalVatPrice,
                    final_amound: finalAmound,
                    vat_rate: `${vatRate.value}%`
                }

            }).done(function (response) {
                console.log(response)
            }).fail(function () {
                console.log('error')
            })
            $('#form')[0].reset();



        }

    )



// input number button style

 
    window.inputNumber = function(el) {
  
      var min = el.attr('min') || false;
      var max = el.attr('max') || false;
  
      var els = {};
  
      els.dec = el.prev();
      els.inc = el.next();
  
      el.each(function() {
        init($(this));
      });
  
      function init(el) {
  
        els.dec.on('click', decrement);
        els.inc.on('click', increment);
  
        function decrement() {
          var value = el[0].value;
          value--;
          if(!min || value >= min) {
            el[0].value = value;
          }
        }
  
        function increment() {
          var value = el[0].value;
          value++;
          if(!max || value <= max) {
            el[0].value = value++;
          }
        }
      }
    }
    inputNumber($('.input-number'));
  })
  
