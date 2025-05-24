<template>
    <div>
        <header>
            <nav>
                <ul>
                    <li><router-link to="/">Home</router-link></li>
                </ul>
            </nav>
        </header>
        <main>
            <slot></slot>
        </main>
        <footer>
            <p>&copy; 2023 Your Company</p>
        </footer>
    </div>
</template>

<script setup>
import { computed } from 'vue';
</script>

<style scoped>
/* Add your styles here */
</style>