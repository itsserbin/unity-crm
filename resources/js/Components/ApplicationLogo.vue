<script setup>
</script>

<template>
    <div class="flex items-center justify-center">
        <div class="app-logo"></div>
    </div>
</template>

<style scoped>
.app-logo {
    transform: rotateZ(45deg);
    perspective: 1000px;
    border-radius: 50%;
    color: #3F51B5;
    width: 100%;
    height: 100%;
}

.app-logo:before,
.app-logo:after {
    content: '';
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: inherit;
    height: inherit;
    border-radius: 50%;
    transform: rotateX(70deg);
    animation: 6s spin linear infinite;
}

.app-logo:after {
    color: #FF3D00;
    transform: rotateY(70deg);
    animation-delay: .9s;
}

@keyframes rotate {
    0% {
        transform: translate(-50%, -50%) rotateZ(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotateZ(360deg);
    }
}

@keyframes rotateccw {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(-360deg);
    }
}

@keyframes spin {
    0%,
    100% {
        box-shadow: .2em 0px 0 0px currentcolor;
    }
    12% {
        box-shadow: .2em .2em 0 0 currentcolor;
    }
    25% {
        box-shadow: 0 .2em 0 0px currentcolor;
    }
    37% {
        box-shadow: -.2em .2em 0 0 currentcolor;
    }
    50% {
        box-shadow: -.2em 0 0 0 currentcolor;
    }
    62% {
        box-shadow: -.2em -.2em 0 0 currentcolor;
    }
    75% {
        box-shadow: 0px -.2em 0 0 currentcolor;
    }
    87% {
        box-shadow: .2em -.2em 0 0 currentcolor;
    }
}
</style>
