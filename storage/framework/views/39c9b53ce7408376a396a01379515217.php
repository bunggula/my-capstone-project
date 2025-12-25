

<?php $__env->startSection('title', 'Login - LAOVISTA Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-screen flex items-center justify-center bg-[#05092a] overflow-hidden relative">

    
    <div class="absolute inset-0 bg-gradient-to-br from-[#0b0c1e] via-[#1a1c3e] to-[#0d0f2a] z-0"></div>

    
    <canvas id="particleCanvas" class="absolute inset-0 z-0"></canvas>

    
    <div class="absolute w-96 h-96 bg-purple-500/20 blur-3xl rounded-full top-10 left-20 animate-pulse-slow"></div>
<div class="absolute w-80 h-80 bg-indigo-400/20 blur-3xl rounded-full bottom-20 right-10 animate-pulse-slow"></div>


    <div class="w-full max-w-md p-8 bg-white rounded shadow-md transform transition-all duration-700 animate-slide-up relative z-10">

       <div class="flex justify-center mb-4 animate-float">
    <?php if($municipality && $municipality->logo): ?>
        <img src="<?php echo e(asset('storage/' . $municipality->logo)); ?>" alt="<?php echo e($municipality->name); ?> Logo" class="h-24 w-auto">
    <?php else: ?>
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Default Logo" class="h-24 w-auto">
    <?php endif; ?>
</div>

        <h2 class="text-center text-2xl font-bold text-blue-700 mb-2 tracking-wide animate-fade-in">
            Welcome
        </h2>
<p class="text-center text-sm text-gray-600 mb-6 animate-fade-in">
    Virtual Information System for Municipality of 
    <span class="font-semibold text-blue-800">
        <?php echo e($municipality->name ?? 'Your Municipality'); ?>

    </span>
</p>


        <?php if(session('error')): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded animate-fade-in">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.post')); ?>" autocomplete="off" class="animate-fade-in">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="<?php echo e(old('email')); ?>"
                    required
                    autofocus
                    autocomplete="off"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                />
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-6 relative">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                />
                <button type="button" id="togglePassword" class="absolute right-3 top-9 text-gray-600 hover:text-gray-900 focus:outline-none transition transform hover:scale-110 duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

           <button
    type="submit"
    id="loginButton"
    class="w-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 focus:ring-4 focus:ring-blue-400 transition transform hover:scale-[1.03] duration-300 animate-fade-in flex justify-center items-center gap-2"
>
    <span id="buttonText">Login</span>
    <svg id="buttonSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
</button>



            <div class="mt-4 text-center">
                <a href="<?php echo e(route('password.request')); ?>" class="text-blue-600 hover:underline hover:text-blue-800 transition duration-300">
                    Forgot your password?
                </a>
            </div>

            <?php if($errors->any() && !$errors->has('email') && !$errors->has('password')): ?>
                <p class="mt-4 text-red-600 text-center text-sm animate-fade-in">
                    <?php echo e(implode(', ', $errors->all())); ?>

                </p>
            <?php endif; ?>
        </form>
    </div>
</div>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes pulse-slow { 0%,100%{opacity:0.25;transform:scale(1);}50%{opacity:0.35;transform:scale(1.05);} }

    .animate-slide-up { animation: slide-up 0.8s ease-out forwards; }
    .animate-fade-in { animation: fade-in 1.2s ease-in-out forwards; }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-pulse-slow { animation: pulse-slow 6s ease-in-out infinite; }

    /* Button pressed effect */
button:active {
    transform: scale(0.98);
}

/* Spinner already uses Tailwind's animate-spin */

</style>

<script>
const loginButton = document.getElementById('loginButton');
const buttonText = document.getElementById('buttonText');
const buttonSpinner = document.getElementById('buttonSpinner');

loginButton.addEventListener('click', (e) => {
    const form = loginButton.closest('form');

    // If form is valid, show spinner
    if (form.checkValidity()) {
        buttonText.classList.add('opacity-0'); // hide text
        buttonSpinner.classList.remove('hidden'); // show spinner
        loginButton.disabled = true; // prevent double click
        loginButton.classList.add('scale-95'); // subtle shrink

        // Let form submit normally
        form.submit();
    }
});

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    });

  // ===== PARTICLE GALAXY EFFECT WITH SHOOTING STARS =====
const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = [];
const particleCount = 120;
const shootingStars = [];

// Generate regular particles
for(let i=0;i<particleCount;i++){
    particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() * 1.5 + 0.5,
        speedX: (Math.random() - 0.5) * 0.3,
        speedY: (Math.random() - 0.5) * 0.3,
        alpha: Math.random() * 0.7 + 0.3
    });
}

// Function to create a shooting star
function createShootingStar(){
    shootingStars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height / 2, // top half of screen
        len: Math.random() * 100 + 50,
        speed: Math.random() * 5 + 5,
        angle: Math.random() * 0.3 - 0.15, // slight diagonal
        alpha: Math.random() * 0.5 + 0.5
    });
}

// Occasionally create shooting stars
setInterval(() => {
    if(Math.random() < 0.3){ // ~30% chance every 1.5s
        createShootingStar();
    }
}, 1500);

function animateParticles(){
    ctx.clearRect(0,0,canvas.width,canvas.height);

    // Draw regular particles
    for(let i=0;i<particles.length;i++){
        let p = particles[i];
        p.x += p.speedX;
        p.y += p.speedY;

        if(p.x < 0) p.x = canvas.width;
        if(p.x > canvas.width) p.x = 0;
        if(p.y < 0) p.y = canvas.height;
        if(p.y > canvas.height) p.y = 0;

        ctx.beginPath();
        ctx.arc(p.x,p.y,p.radius,0,Math.PI*2);
        ctx.fillStyle = `rgba(255,255,255,${p.alpha})`;
        ctx.fill();
    }

    // Draw shooting stars
    for(let i = shootingStars.length - 1; i >= 0; i--){
        let s = shootingStars[i];
        ctx.beginPath();
        ctx.moveTo(s.x, s.y);
        ctx.lineTo(s.x - s.len * Math.cos(s.angle), s.y - s.len * Math.sin(s.angle));
        ctx.strokeStyle = `rgba(255,255,255,${s.alpha})`;
        ctx.lineWidth = 2;
        ctx.stroke();

        s.x += s.speed * Math.cos(s.angle);
        s.y += s.speed * Math.sin(s.angle);
        s.alpha -= 0.005;

        // Remove when invisible or off screen
        if(s.alpha <= 0 || s.x > canvas.width || s.y > canvas.height){
            shootingStars.splice(i, 1);
        }
    }

    requestAnimationFrame(animateParticles);
}

animateParticles();

window.addEventListener('resize', ()=>{
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/login.blade.php ENDPATH**/ ?>