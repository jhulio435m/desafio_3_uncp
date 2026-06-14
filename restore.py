import json

log_path = "/home/blink/.gemini/antigravity/brain/7a551905-3875-4206-b817-29bf25547321/.system_generated/logs/overview.txt"

with open(log_path, 'r', encoding='utf-8') as f:
    for line in f:
        try:
            data = json.loads(line)
            if data.get('source') == 'TOOL' and data.get('type') == 'TOOL_RESPONSE':
                for res in data.get('tool_responses', []):
                    if res.get('name') == 'view_file':
                        output = res.get('response', {}).get('output', '')
                        if 'dashboard.blade.php' in output and 'Showing lines 1 to 321' in output:
                            with open('dashboard_dump.txt', 'w') as out_f:
                                out_f.write(output)
                                print("Extracted dashboard 321 lines!")
                        if 'app.blade.php' in output and 'Showing lines 1 to 110' in output:
                            with open('app_dump.txt', 'w') as out_f:
                                out_f.write(output)
                                print("Extracted app 110 lines!")
        except Exception as e:
            pass
