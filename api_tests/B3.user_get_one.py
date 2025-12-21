import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

# Load User ID created in B1
user_id = utils.load_config("test_user_id")
if not user_id:
    user_id = 1 # Fallback to ID 1 if B1 wasn't run
    print("[INFO] No test_user_id found, defaulting to ID 1")

url = f"{utils.BASE_URL}/users/{user_id}"
output_file = f"{os.path.splitext(os.path.basename(__file__))[0]}.json"

# Load Access Token
token = utils.load_config("access_token")
headers = {"Authorization": f"Bearer {token}"} if token else {}

utils.send_and_print(
    url,
    headers=headers,
    method="GET",
    output_file=output_file
)