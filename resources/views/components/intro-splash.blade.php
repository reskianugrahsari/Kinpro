<style>
    #kinpro-intro-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    @media (max-aspect-ratio: 1/1) {
        #kinpro-intro-video {
            object-fit: contain;
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 14%, #000 86%, transparent 100%);
            mask-image: linear-gradient(to bottom, transparent 0%, #000 14%, #000 86%, transparent 100%);
        }
    }
</style>

<div id="kinpro-intro-splash"
     class="fixed inset-0 z-[100000] hidden items-center justify-center overflow-hidden transition-opacity duration-500 ease-out opacity-100 select-none"
     style="background: radial-gradient(circle at center, #f4f4f6 0%, #e4e4e6 75%, #dcdce0 100%);"
     role="dialog"
     aria-modal="true"
     aria-label="Animasi Pembuka KinPro">
    <video id="kinpro-intro-video"
           class="fixed inset-0 w-screen h-screen pointer-events-none"
           autoplay
           muted
           playsinline
           preload="auto">
        <source src="{{ asset('videos/animasi-kinpro.mp4') }}?v={{ file_exists(public_path('videos/animasi-kinpro.mp4')) ? filemtime(public_path('videos/animasi-kinpro.mp4')) : '1' }}" type="video/mp4">
    </video>
</div>

<script>
    (function() {
        const INTRO_KEY = 'kinpro_intro_played';
        const splash = document.getElementById('kinpro-intro-splash');
        const video = document.getElementById('kinpro-intro-video');
        if (!splash || !video) return;

        let alreadyPlayed = false;
        try {
            alreadyPlayed = sessionStorage.getItem(INTRO_KEY) === '1';
        } catch (e) {}

        if (alreadyPlayed) {
            splash.remove();
            return;
        }

        try {
            sessionStorage.setItem(INTRO_KEY, '1');
        } catch (e) {}

        splash.classList.remove('hidden');
        splash.classList.add('flex');

        let dismissed = false;
        let safetyTimer = setTimeout(dismissSplash, 4500);

        function dismissSplash() {
            if (dismissed) return;
            dismissed = true;
            if (safetyTimer) {
                clearTimeout(safetyTimer);
                safetyTimer = null;
            }
            splash.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(function() {
                try {
                    video.pause();
                } catch (e) {}
                splash.remove();
            }, 500);
        }

        video.addEventListener('playing', function() {
            if (dismissed) return;
            if (safetyTimer) clearTimeout(safetyTimer);
            safetyTimer = setTimeout(dismissSplash, 11000);
        }, { once: true });

        video.addEventListener('ended', dismissSplash, { once: true });
        video.addEventListener('error', dismissSplash, { once: true });

        document.addEventListener('keydown', function onKey(e) {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', onKey);
                dismissSplash();
            }
        });

        const playPromise = video.play();
        if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(function() {
                dismissSplash();
            });
        }
    })();
</script>
