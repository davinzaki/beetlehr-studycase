<script setup>
import axios from "axios";
import dayjs from "dayjs";
import { ref } from "vue";
import { bool, object } from "vue-types";
import { notify } from "notiwind";
import VDialog from "@/components/VDialog/index.vue";
import VButton from "@/components/VButton/index.vue";
import VSelect from "@/components/VSelect/index.vue";
import VInput from "@/components/VInput/index.vue";
import VSwitch from "@/components/VSwitch/index.vue";
import VCheckbox from "@/components/VCheckbox/index.vue";

const props = defineProps({
    openDialog: bool(),
    updateAction: bool().def(false),
    data: object().def({}),
    additional: object().def({}),
});

const emit = defineEmits(["close", "successSubmit"]);

const description = ref("");
const fingerprintSelectHandle = ref();
const typeSelectHandle = ref();
const isLoading = ref(false);
const formError = ref({});
const form = ref({});
const typeOptions = ref({
    description: "Description",
    document: "Document",
});

const openForm = () => {
    form.value = ref({});
};

const closeForm = () => {
    form.value = ref({});
    formError.value = ref({});
    // fingerprintSelectHandle.value.clearSelected();
};

const submit = async () => {
    const fd = new FormData();
    if (form.value.file != null) {
        fd.append("file", form.value.file, form.value.file.name);
    }

    Object.keys(form.value).forEach((key) => {
        fd.append(key, form.value[key]);
    });

    fd.append("description", description.value);

    if (props.updateAction) {
        update(fd);
    } else {
        create(fd);
    }
};

const update = async (fd) => {
    isLoading.value = true;
    axios
        .post(route("fingerprint.update", { id: props.data.id }), fd)
        .then((res) => {
            emit("close");
            emit("successSubmit");
            form.value = ref({});
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
        })
        .catch((res) => {
            // Handle validation errors
            const result = res.response.data;
            const metaError = res.response.data.meta?.error;
            if (result.hasOwnProperty("errors")) {
                formError.value = ref({});
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }

            if (metaError) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: metaError,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: result.message,
                    },
                    2500
                );
            }
        })
        .finally(() => (isLoading.value = false));
};

const create = async (fd) => {
    isLoading.value = true;
    axios
        .post(route("fingerprint.create"), fd)
        .then((res) => {
            emit("close");
            emit("successSubmit");
            form.value = ref({});
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.meta.message,
                },
                2500
            );
        })
        .catch((res) => {
            // Handle validation errors
            const result = res.response.data;
            const metaError = res.response.data.meta?.error;
            if (result.hasOwnProperty("errors")) {
                formError.value = ref({});
                Object.keys(result.errors).map((key) => {
                    formError.value[key] = result.errors[key].toString();
                });
            }

            if (metaError) {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: metaError,
                    },
                    2500
                );
            } else {
                notify(
                    {
                        type: "error",
                        group: "top",
                        text: result.message,
                    },
                    2500
                );
            }
        })
        .finally(() => (isLoading.value = false));
};
</script>

<template>
    <VDialog
        :showModal="openDialog"
        :title="updateAction ? 'Update Fingerprint' : 'Create Fingerprint'"
        @opened="openForm"
        @closed="closeForm"
        size="xl"
    >
        <template v-slot:close>
            <button
                class="text-slate-400 hover:text-slate-500"
                @click="$emit('close')"
            >
                <div class="sr-only">Close</div>
                <svg class="w-4 h-4 fill-current">
                    <path
                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"
                    />
                </svg>
            </button>
        </template>
        <template v-slot:content>
            <div class="grid grid-cols-1 gap-3">
                <VInput
                    placeholder="Insert Name"
                    label="Fingerprint Name"
                    :required="true"
                    v-model="form.name"
                    :errorMessage="formError.name"
                    @update:modelValue="formError.name = ''"
                />
                <VInput
                    placeholder="Insert Serial Number"
                    label="Fingerprint Serial Number"
                    :required="true"
                    v-model="form.serial_number"
                    :errorMessage="formError.serial_number"
                    @update:modelValue="formError.serial_number = ''"
                />

                <!-- <VSwitch v-model="form.is_active" label="is_active" /> -->

                <!-- <VSelect
                    placeholder="Choose Status"
                    :required="true"
                    v-model="form.is_active"
                    :options="additional.is_active"
                    label="Role"
                    :errorMessage="formError.is_active"
                    @update:modelValue="formError.is_active = ''"
                /> -->

                <!-- <VCheckboxx
                    v-model="form.is_active"
                    label="Active"
                    @update:modelValue="formError.is_active = ''"
                /> -->
            </div>
            <!-- <VInput placeholder="Insert Name" label="Employee Name" :required="true"
                        v-model="form.employee_name" :errorMessage="formError.employee_name"
                        @update:modelValue="formError.employee_name = ''" />

             -->
        </template>
        <template v-slot:footer>
            <div class="flex flex-wrap justify-end space-x-2">
                <VButton
                    label="Cancel"
                    type="default"
                    @click="$emit('close')"
                />
                <VButton
                    :is-loading="isLoading"
                    :label="updateAction ? 'Update' : 'Create'"
                    type="primary"
                    @click="submit"
                />
                <!-- <VBadge
                    :text="data.is_active_formatted"
                    :color="
                        data.is_active_formatted === 'yes'
                            ? 'success'
                            : 'danger'
                    "
                    size="sm"
                />

                <VSwitch v-model="form.is_active" label="is_active" /> -->
            </div>
        </template>
    </VDialog>
</template>

<style>
.dp__select {
    color: #4f8cf6 !important;
}

.date_error {
    --dp-border-color: #dc3545 !important;
}
</style>
