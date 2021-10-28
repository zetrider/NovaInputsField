Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-inputs-field', require('./components/IndexField'))
  Vue.component('detail-nova-inputs-field', require('./components/DetailField'))
  Vue.component('form-nova-inputs-field', require('./components/FormField'))
})
