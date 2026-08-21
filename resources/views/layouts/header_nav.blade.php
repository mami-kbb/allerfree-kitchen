<nav>
    @if( in_array(Route::currentRouteName(), ['recipes.list']) )
    <div class="text-center m-auto p-4">
        <form class="text-center m-auto p-4" action="{{ route('recipes.list')}}" method="get">
            <div class="inline-flex items-start">
                <div class="relative">
                    <input class="bg-white w-64 md:w-78 border rounded-md p-2" type="text" id="keywordInput" name="keyword" value="{{ request('keyword') }}" placeholder="食材・レジピ名でさがす" autocomplete="off">

                    <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">

                    <div class="absolute left-0 top-full w-64 md:w-78 bg-white border border-gray-200 text-sm z-10" id="allergyDropdown" style="display: none;">
                        <div class="m-2">
                            <p class="text-left my-2" id="selectedAllergy">
                                除外アレルギー：{{ $selectedAllergies->implode(', ') ? : '未選択' }}
                            </p>
                            <p class="text-left" id="selectedAllergyCategory">
                                除外アレルギーカテゴリー：{{ $selectedCategories->implode(', ') ? : '未選択' }}
                            </p>
                            <button class="bg-white hover:shadow-md border border-accent text-accent px-2 m-2 rounded-md font-semibold" type="button" id="openModal"> 詳細条件</button>
                        </div>
                        <div class="m-2">
                            <p class="text-xs text-orange-900">※本サービスはレシピに登録された食材情報をもとに検索しています。<br>加工食品や調味料の原材料までは判定対象外です。</p>
                        </div>
                    </div>
                </div>
                <button class="bg-taupe-200 hover:bg-taupe-300 active:bg-taupe-400 text-accent px-4 py-2 mx-2 rounded-md font-semibold shadow-md" type="submit">検索</button>
            </div>

            <div class="fixed inset-0 z-50 bg-black/50 items-center justify-center" id="modal" style="display: none;">
                <div class="relative bg-white rounded-md w-126 p-6">
                    <span class="absolute top-2 right-3 cursor-pointer" id="closeModal">&times;</span>
                    <p class="my-2">除外するアレルギーを選択</p>
                    <input type="hidden" name="allergy_recipe[]" value="0">
                    <div class="flex flex-wrap gap-2">
                        @foreach($allergies as $allergy)
                        <div>
                            <input class="peer sr-only allergy-checkbox" type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" {{ in_array($allergy->id, $excludeAllergies) ? 'checked' : '' }} name="allergy_recipe[]" data-name="{{ $allergy->name }}">
                            <label class="block border border-gray-300 bg-white rounded-full px-3 py-1 text-sm cursor-pointer peer-checked:bg-taupe-300" for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <p class="my-4">除外するアレルギーカテゴリーを選択</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($allergyCategories as $allergyCategory)
                            <div>
                                <input class="peer sr-only category-checkbox" type="checkbox" id="category_{{ $allergyCategory->id }}" value="{{ $allergyCategory->id }}" {{ in_array($allergyCategory->id, $excludeCategories) ? 'checked' : '' }} name="allergy_category[]" data-category="{{ $allergyCategory->category }}" class="category-checkbox">
                                <label class="block border border-gray-300 bg-white text-sm rounded-full cursor-pointer px-3 py-1 peer-checked:bg-taupe-300" for="category_{{ $allergyCategory->id }}">{{ $allergyCategory->category }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="bg-white hover:shadow-md border border-accent text-accent px-4 py-2 mx-2 rounded-md font-semibold" type="button" id="applyAllergy">設定</button>
                </div>
            </div>
        </form>
    </div>
    @endif
</nav>

<script>
    const keywordInput = document.getElementById('keywordInput');
    const dropdown = document.getElementById('allergyDropdown');
    const modal = document.getElementById('modal');
    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const applyBtn = document.getElementById('applyAllergy');
    const selectedText = document.getElementById('selectedAllergy');
    const selectedCategoryText = document.getElementById('selectedAllergyCategory');

    keywordInput.addEventListener('focus', () => {
        dropdown.style.display = 'block';
    });
    openBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        modal.style.display = 'flex';
    });
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    applyBtn.addEventListener('click', () => {
        const allergyChecked = document.querySelectorAll('.allergy-checkbox:checked');
        const names = Array.from(allergyChecked).map(el => el.dataset.name);

        if (names.length > 0) {
            selectedText.textContent = "除外アレルギー：" + names.join('，');
        } else {
            selectedText.textContent = "除外アレルギー：未選択";
        }

        const categoryChecked = document.querySelectorAll('.category-checkbox:checked');
        const categories = Array.from(categoryChecked).map(el => el.dataset.category);

        if (categories.length > 0) {
            selectedCategoryText.textContent = "除外アレルギーカテゴリー：" + categories.join('，');
        } else {
            selectedCategoryText.textContent = "除外アレルギーカテゴリー：未選択";
        }

        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (!keywordInput.contains(e.target) && !dropdown.contains(e.target) && !modal.contains(e.target)) {
            dropdown.style.display = 'none';
        }
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>