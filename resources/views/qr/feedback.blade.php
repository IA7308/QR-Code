<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $destination->place_name }} - Umpan Balik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-xl items-center px-4 py-10">
        <article class="w-full rounded-2xl bg-white p-6 shadow-sm sm:p-10">
            <p class="text-xs font-bold uppercase tracking-wide text-green-700">{{ $unit->template->name }} · {{ $unit->unit_code }}</p>
            <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Bagaimana pengalaman Anda?</h1>
            <p class="mt-3 leading-7 text-gray-600">Pilih rating dan tulis ulasan opsional. Setelah dikirim, feedback tersimpan di aplikasi dan Anda dapat membuka Google Review.</p>
            @if ($feedbackSubmitted)
                <div class="mt-5 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="status">Terima kasih, feedback Anda sudah tersimpan.</div>
                <a href="{{ $destination->review_url }}" target="_blank" rel="noopener noreferrer" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="mt-5 block w-full rounded-lg px-5 py-3 text-center font-semibold hover:brightness-90">Lanjut ke Google Review</a>
            @else
                <form id="feedback-form" method="POST" action="{{ $feedbackUrl }}" data-review-url="{{ $destination->review_url }}" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
                    <fieldset>
                        <legend class="mb-2 block text-sm font-semibold text-gray-700">Rating Anda</legend>
                        <div id="star-group" class="flex items-center gap-1" role="radiogroup" aria-label="Rating 1 sampai 5 bintang">
                            @foreach (range(1, 5) as $rating)
                                <label class="star-label cursor-pointer rounded-lg p-1 transition-transform duration-150 hover:scale-110 active:scale-95 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-green-600" title="{{ $rating }} bintang">
                                    <input class="sr-only" type="radio" name="rating" value="{{ $rating }}" required>
                                    <span class="sr-only">{{ $rating }} bintang</span>
                                    <svg class="star h-11 w-11 text-gray-300 drop-shadow-sm transition-colors duration-150" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endforeach
                        </div>
                        <p id="rating-label" class="mt-2 h-5 text-sm font-semibold text-gray-500" aria-live="polite">Ketuk bintang untuk menilai</p>
                    </fieldset>
                    <div id="comment-wrapper" class="hidden">
                        <label for="comment" class="mb-1 block text-sm font-semibold text-gray-700">Ulasan (opsional)</label>
                        <textarea id="comment" name="comment" rows="4" maxlength="2000" disabled class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Ceritakan pengalaman Anda"></textarea>
                    </div>
                    <button type="submit" style="background-color:#15803d;color:#ffffff;border:1px solid #166534;" class="block w-full rounded-lg px-5 py-3 text-center font-semibold hover:brightness-90">Kirim </button>
                </form>
                @endif
            </article>
        </main>

        @unless ($feedbackSubmitted)
        <script>
            (function () {
                const form = document.querySelector('form[method="POST"]');
                if (!form) return;

                const wrapper = document.getElementById('comment-wrapper');
                const comment = document.getElementById('comment');
                const stars = Array.from(form.querySelectorAll('.star'));
                const radios = Array.from(form.querySelectorAll('input[name="rating"]'));
                const label = document.getElementById('rating-label');
                const defaultText = label.textContent;
                const texts = ['', 'Sangat buruk', 'Kurang baik', 'Cukup', 'Baik', 'Luar biasa!'];
                let submitted = false;

                function paint(count) {
                    stars.forEach(function (star, i) {
                        const on = i < count;
                        star.classList.toggle('text-yellow-400', on);
                        star.classList.toggle('text-gray-300', !on);
                    });
                    label.textContent = count ? texts[count] : defaultText;
                }

                function current() {
                    const checked = form.querySelector('input[name="rating"]:checked');
                    return checked ? parseInt(checked.value, 10) : 0;
                }

                function toggleComment(show) {
                    wrapper.classList.toggle('hidden', !show);
                    comment.disabled = !show;
                }

                radios.forEach(function (radio, i) {
                    const lbl = radio.closest('label');
                    lbl.addEventListener('mouseenter', function () { paint(i + 1); });
                    lbl.addEventListener('mouseleave', function () { paint(current()); });

                    radio.addEventListener('change', function () {
                        paint(i + 1);
                        if (radio.value === '3' || radio.value === '4' || radio.value === '5') {
                            toggleComment(false);
                            if (submitted) return;
                            submitted = true;
                            form.requestSubmit();
                        } else {
                            toggleComment(true);
                        }
                    });
                });

                form.addEventListener('mouseleave', function () { paint(current()); });

                // Pulihkan tampilan jika browser mengembalikan pilihan (tombol back)
                const initial = current();
                paint(initial);
                if (initial && initial !== 5) toggleComment(true);
            })();
        </script>
        @endunless
    </body>
</html>
