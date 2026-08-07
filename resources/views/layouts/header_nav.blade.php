<nav>
    @if( in_array(Route::currentRouteName(), ['recipes.list']) )
    <div>
        <form action="{{ route('recipes.list')}}" method="get">
            <input type="text" id="keywordInput" name="keyword" value="{{ request('keyword') }}" placeholder="食材・レジピ名でさがす" autocomplete="off">

            <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">

            <div id="allergyDropdown" style="display: none;">
                <div>
                    <p id="selectedAllergy">
                        除外アレルギー：{{ $selectedAllergies->implode(', ') ? : '未選択' }}
                    </p>
                    <p id="selectedAllergyCategory">
                        除外アレルギーカテゴリー：{{ $selectedCategories->implode(', ') ? : '未選択' }}
                    </p>
                    <button type="button" id="openModal"> 詳細条件</button>
                </div>
                <div>
                    <p>※本サービスはレシピに登録された食材情報をもとに検索しています。<br>加工食品や調味料の原材料までは判定対象外です。</p>
                </div>
            </div>

            <div id="modal" style="display: none;">
                <div>
                    <span class="close" id="closeModal">&times;</span>
                    <p>除外するアレルギーを選択</p>
                    <input type="hidden" name="allergy_recipe[]" value="0">
                    <div>
                        @foreach($allergies as $allergy)
                        <input type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" {{ in_array($allergy->id, $excludeAllergies) ? 'checked' : '' }} name="allergy_recipe[]" data-name="{{ $allergy->name }}" class="allergy-checkbox">
                        <label for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                        @endforeach
                    </div>
                    <div>
                        <p>除外するアレルギーカテゴリーを選択</p>
                        @foreach($allergyCategories as $allergyCategory)
                        <input type="checkbox" id="category_{{ $allergyCategory->id }}" value="{{ $allergyCategory->id }}" {{ in_array($allergyCategory->id, $excludeCategories) ? 'checked' : '' }} name="allergy_category[]" data-category="{{ $allergyCategory->category }}" class="category-checkbox">
                        <label for="category_{{ $allergyCategory->id }}">{{ $allergyCategory->category }}</label>
                        @endforeach
                    </div>
                    <button type="button" id="applyAllergy">設定</button>
                </div>
            </div>
            <button type="submit">検索</button>
        </form>
    </div>
    @endif
    <ul>
        @auth
        <li><a href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
        <li><a href="{{ route('recipe.create') }}">レシピ投稿</a></li>
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button>ログアウト</button>
            </form>
        </li>
        @else
        <li><a href="{{ route('login') }}">ログイン</a></li>
        <li><a href="{{ route('register') }}">新規登録</a></li>
        @endauth
    </ul>
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