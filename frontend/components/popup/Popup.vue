<template>
    <Teleport to="body">
        <Transition name="popup">
            <div v-if="isOpen(name)" class="c-popup" @click.self="closePopup">
                <div class="c-popup__content">
                    <button class="c-popup__close" @click="closePopup">
                        <PhX :size="24" />
                    </button>
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { PhX } from '@phosphor-icons/vue'

const props = defineProps({
    name: {
        type: String,
        required: true
    }
})

const { isOpen, closePopup } = usePopup()
</script>

<style src="./style.css"></style>