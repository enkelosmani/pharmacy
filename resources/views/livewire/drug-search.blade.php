<div style="max-width: 900px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 10px;">
    <h1 style="font-size: 28px; font-weight: bold; text-align: center; color: #333333; margin-bottom: 30px;">Aplikacioni për Kërkimin e Ilaçeve</h1>

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" wire:model.debounce.500ms="ndcCodes" placeholder="Shkruaj kodet të ndara me presje, 12345-6789, 11111-2222, 99999-0000" style="flex: 1; padding: 12px; border: 1px solid #cccccc; border-radius: 5px; font-size: 16px; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cccccc'">
        @auth
            <button wire:click="search" style="background-color: #3b82f6; color: #ffffff; padding: 12px 25px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Kërko</button>
        @else
            <button disabled style="background-color: #cccccc; color: #666666; padding: 12px 25px; border: none; border-radius: 5px; font-size: 16px; cursor: not-allowed;">Kërko</button>
        @endauth
    </div>

    @if ($loading)
        <div style="display: flex; justify-content: center; margin: 20px 0;">
            <div style="width: 30px; height: 30px; border: 4px solid #3b82f6; border-top: 4px solid transparent; border-right: 4px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;" role="status"></div>
        </div>
    @endif

    @if (!empty($results))
        <div style="overflow-x: auto; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; background-color: #f5f5f5; border: 1px solid #e0e0e0;">
                <thead>
                <tr>
                    <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Kodi</th>
                    <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Emri i produktit</th>
                    <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Prodhuesi</th>
                    <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Lloji i produktit</th>
                    <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Burimi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($results as $result)
                    <tr style="background-color: #ffffff; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='#ffffff'">
                        <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $result['ndc_code'] }}</td>
                        <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $result['brand_name'] }}</td>
                        <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $result['labeler_name'] }}</td>
                        <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $result['product_type'] }}</td>
                        <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px; background-color: @if($result['source'] === 'Not Found') #f0f0f0 @else #ffffff @endif">{{ $result['source'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <button wire:click="export" style="width: 100%; background-color: #34c759; color: #ffffff; padding: 12px 25px; border-radius: 5px; border: none; font-size: 16px; cursor: pointer; transition: background-color 0.3s;" wire:loading.attr="disabled" onmouseover="this.style.backgroundColor='#2ea44f'" onmouseout="this.style.backgroundColor='#34c759'">
            @if ($exporting) Eksportimi... @else Eksporto në CSV @endif
        </button>
    @endif

    <h2 style="font-size: 20px; font-weight: bold; color: #333333; margin-top: 40px; margin-bottom: 15px;">Ilaçet e Ruajtura</h2>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background-color: #f5f5f5; border: 1px solid #e0e0e0;">
            <thead>
            <tr>
                <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Kodi</th>
                <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Emri i produktit</th>
                <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Prodhuesi</th>
                <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Lloji i produktit</th>
                <th style="padding: 15px; border: 1px solid #e0e0e0; background-color: #ffffff; text-align: center; font-weight: bold; color: #333333; font-size: 16px;">Veprimi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($savedDrugs as $drug)
                <tr style="background-color: #ffffff; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='#ffffff'">
                    <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $drug->ndc_code }}</td>
                    <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $drug->brand_name ?? '-' }}</td>
                    <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $drug->labeler_name ?? '-' }}</td>
                    <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px;">{{ $drug->product_type ?? '-' }}</td>
                    <td style="padding: 15px; border: 1px solid #e0e0e0; text-align: center;">
                        @auth
                            <button wire:click="deleteDrug('{{ $drug->ndc_code }}')" style="color: #e3342f; font-size: 16px; border: none; background: none; cursor: pointer; transition: color 0.3s;" onmouseover="this.style.color='#c53030'" onmouseout="this.style.color='#e3342f'">Fshij</button>
                        @else
                            <span>-</span>
                        @endauth
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 15px; border: 1px solid #e0e0e0; text-align: center; color: #555555; font-size: 16px; background-color: #ffffff;">Nuk ka ilaçe të ruajtura.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px;" wire:ignore>
        {!! $savedDrugs->links() !!}
    </div>
</div>
