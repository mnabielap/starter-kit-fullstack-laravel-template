import sys
import os

sys.path.append(os.path.abspath(os.path.dirname(__file__)))
import utils

# URL with query params
url = f"{utils.BASE_URL}/users?page=1&limit=5&sortBy=created_at:desc"
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