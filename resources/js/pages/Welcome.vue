<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const form = useForm({
    file: '',
});
const uploading = ref(false);
const message = ref('');
let light = 0;
let result = '';


async function handleFileUpload(event) {
    result = ''; // reset result
    light = 0; // reset light to neutral
    if (form.file.length === 0) {
        alert('Please select a CSV file to upload.');
        event.preventDefault();
        return;
    }

    // upload button clicked, we start uploading
    uploading.value = true;

    // we need to create FormData object to send the file to the server
    // as useForm does not support file uploads directly
    const formData = new FormData();
    formData.append('csv', form.file);

    // prevent the default form submission
    // as we are handling the upload via AJAX
    event.preventDefault();

    try {
        const res = await axios.post('/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        if (res.data.status_code !== 200) {
            light = -1; // red light
        }

        if (res.data.status_code == 200) {
            light = 1; // green light
            const persons = res.data.persons;

            for (const person of persons) {
                for (const [key, value] of Object.entries(person)) {
                    result += `$person[${key}]: ${value}\n`;
                }
                result += `\n`;
            }
        }

        //success or error message
        message.value = res.data.message;

        // uploading is done
        uploading.value = false;
    } catch (error) {
        console.log('Error: ' + error);
        message.value = 'Failed to upload';
    } finally {
        uploading.value = false;
    }
}
</script>

<template>
    <Head title="Upload CSV File" />
    <main class="main-wrapper">
        <div>
            <h1 class="text-2xl font-bold">Welcome to the task solution by Imad Sayed</h1>
            <p class="mt-4">Please upload your CSV file to get started.</p>
            <form @submit.prevent="handleFileUpload" class="form-group" method="POST" enctype="multipart/form-data">
                <input type="file" name="csv" accept=".csv" class="form-control" v-on:change="form.file = $event.target.files[0]" required />
                <button type="submit" class="btn" v-on:click="handleFileUpload" :disabled="uploading">Upload File</button>
            </form>
            <p class="message" :class="{'warning': light < 0, 'success': light > 0 }">{{ message }}</p>
            <p class="result"><pre>{{ result }}</pre></p>
        </div>
    </main>
</template>

<style scoped>
main.main-wrapper {
    max-width: 100%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
main.main-wrapper > div {
    width: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}
main.main-wrapper > div form.form-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}
main.main-wrapper > div form.form-group input[type='file'] {
    border: 1px solid #f1f1f1;
    border-radius: 0.25rem;
    padding: 0.125rem;
}
main.main-wrapper > div form.form-group button.btn {
    margin-top: 10px;
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
}
main.main-wrapper > div form.form-group button.btn:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}
main.main-wrapper > div p.message {
    margin-top: 1.25rem;
    font-size: 1.2rem;
    color: white;
}
main.main-wrapper > div p.message.warning {
    color: red;
    border: 1px solid red;
    padding: 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(255, 0, 0, 0.1);
}
main.main-wrapper > div p.message.success {
    color: green;
    border: 1px solid green;
    padding: 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(0, 255, 0, 0.1);
}
main.main-wrapper > div p.result {
    margin-top: 1.25rem;
    font-size: 1rem;
    color: white;
    white-space: pre-wrap;
    text-align: left;
}
</style>
