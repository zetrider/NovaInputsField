<template>
  <div>
    <div class="border-t border-40" v-for="(values, valueIndex) in value" :key="valueIndex">
        <div v-for="(template, templateKey) in field.templates" :key="templateKey">
          {{ template.attributes["placeholder"] || templateKey }}: {{ values[templateKey] || '-' }}
        </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["resourceName", "field"],
  data() {
    return {
      value: [],
    };
  },
  mounted() {
    this.value = this.field.value || [];
  },
};
</script>
