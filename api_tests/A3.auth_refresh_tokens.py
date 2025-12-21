import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

url = f"{utils.BASE_URL}/auth/refresh-tokens"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Load Refresh Token from config
refresh_token = utils.load_config("refresh_token")

if not refresh_token:
    print("[ERROR] No refresh_token found in secrets.json. Please login first.")
    sys.exit(1)

payload = {
    "refreshToken": refresh_token
}

response = utils.send_and_print(
    url,
    method="POST",
    body=payload,
    output_file=output_file
)

# Save NEW tokens if successful
if response.status_code == 200:
    data = response.json()
    if 'tokens' in data:
        utils.save_config("access_token", data['tokens']['access']['token'])
        utils.save_config("refresh_token", data['tokens']['refresh']['token'])
        print("[SUCCESS] New tokens saved to secrets.json")