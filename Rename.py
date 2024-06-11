import os
import shutil

def copy_and_rename_files_based_on_modification_date(source_directory, target_directory):
    # 创建目标目录（如果不存在）
    if not os.path.exists(target_directory):
        os.makedirs(target_directory)
    
    # 获取源目录中的所有文件
    for filename in os.listdir(source_directory):
        file_path = os.path.join(source_directory, filename)
        
        # 检查是否为文件（排除目录）
        if os.path.isfile(file_path):
            # 获取文件的修改时间（时间戳形式）
            mod_time = os.path.getmtime(file_path)
            
            # 获取文件扩展名
            file_extension = os.path.splitext(filename)[1]
            
            # 构造新的文件名（时间戳+扩展名）
            new_filename = f'{int(mod_time)}{file_extension}'
            new_file_path = os.path.join(target_directory, new_filename)
            
            # 复制并重命名文件到目标目录
            shutil.copy2(file_path, new_file_path)
            print(f'Copied and renamed {filename} to {new_filename}')

# 示例源目录和目标目录路径
source_directory_path = './img'
target_directory_path = './img_renamed'

# 根据文件修改日期复制并重命名文件
copy_and_rename_files_based_on_modification_date(source_directory_path, target_directory_path)