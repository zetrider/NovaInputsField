<template>
  <panel-item :field="field">
    <template slot="value">
      <div
        class="border border-40 p-6 pb-3 mb-3 rounded w-full"
        v-for="(values, valueIndex) in value"
        :key="valueIndex"
      >
        <div
          class="mb-3"
          v-for="(template, templateKey) in field.templates"
          :key="templateKey"
        >
          {{ template.attributes["placeholder"] || templateKey }}:
          {{ values[templateKey] || "" }}
        </div>
      </div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],
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
